<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Search card -->
                <card-component title="Busca de marcas">
                    <template v-slot:content>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component id="inputId" title="ID" id-help="idHelp" helpText="Opicional. Informe o ID da marca">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="search.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">

                                <input-container-component id="inputName" title="Nome" id-help="nameHelp" helpText="Opicional. Informe o nome da marca">
                                    <input type="text" class="form-control" id="inputName" aria-describedby="nameHelp" placeholder="Nome" v-model="search.name">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                        
                    <template v-slot:footer>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="searchItens()">Pesquisar</button>
                    </template>
                </card-component>
                <!-- /Search card -->

                <!-- List card -->
                <card-component title="Relação de marcas">
                    <template v-slot:content>
                        <table-component 
                            :data="brands.data" 
                            :titles="{
                                id:{ title: 'ID', type: 'text' }, 
                                name:{ title: 'Nome', type: 'text' }, 
                                image:{ title: 'Logo', type: 'image' },
                                created_at:{ title: 'Data de criação', type: 'date' },
                            }"
                        >
                        </table-component>
                    </template>
                    <template v-slot:footer>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li 
                                        :class="link.active ? 'page-item active' : 'page-item'" 
                                        v-for="link, key in brands.links" :key="key" 
                                        @click="paginate(link)"
                                    >
                                        <a class="page-link" v-html="link.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#brandModal">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- /List card -->
            </div>
        </div>

        <modal-component id="brandModal" title="Adicionar marca">
            <template v-slot:alerts>
                <alert-component type="success" :title="responseTitle" :message="responseMessage" v-if="responseStatus === 'success'"></alert-component>
                <alert-component type="danger" :title="responseTitle" :message="responseMessage" v-if="responseStatus === 'error'"></alert-component>
            </template>
            <template v-slot:content>
                <div class="form-group">
                    <input-container-component id="newName" title="Nome" id-help="NewNameHelp" helpText="Informe o nome da marca">
                        <input type="text" class="form-control" id="newName" aria-describedby="NewNameHelp" placeholder="Nome" v-model="brandName">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component id="newImage" title="Imagem" id-help="newImageHelp" helpText="Selecione uma imagem PNG">
                        <input type="file" class="form-control-file" id="newImage" aria-describedby="newImageHelp" placeholder="Imagem" @change="loadImage($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="save()">Salvar</button>
            </template>
        </modal-component>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                baseUrl: 'http://localhost:8000/api/v1/brand',
                pageUrl: '',
                filterUrl: '',
                brandName: '',
                brandImage: [],
                responseStatus: '',
                responseMessage: {},
                responseTitle: '',
                brands: { data: [] },
                search: {id: '', name: ''}
            }
        },
        computed: {
            token() {
                let token = document.cookie.split(';').find(indice => {
                    return indice.startsWith('token')
                })

                token = token.split('=')[1]
                token = 'Bearer ' + token

                return token
            }
        },
        methods: {
            loadItens() {
                let url = this.baseUrl + '?' + this.pageUrl + this.filterUrl
                console.log(url)
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }
                
                axios.get(url, config)
                    .then(response => {
                        this.brands = response.data
                    })
                    .catch(errors => { console.log(errors) })
            },
            loadImage(e) {
                this.brandImage = e.target.files
            },
            searchItens() {
                let filter = ''
                for(let key in this.search) {
                    if(this.search[key]) {
                        if(filter != '') {
                            filter += ';'
                        }
                        filter += key + ':like:' + filter
                    }
                }

                this.filterUrl = '&filtro='+filtro
            },
            save() {
                let formData = new FormData()
                formData.append('name', this.brandName)
                formData.append('image', this.brandImage[0])

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(this.baseUrl, formData, config)
                    .then(response => {
                        this.responseStatus = 'success'
                        this.responseTitle = 'Marca cadastrada com sucesso!'
                        this.responseMessage = {
                            message: 'ID do registro: ' + response.data.id
                        }
                        console.log(response)
                    })
                    .catch(errors => {
                        this.responseStatus = 'error'
                        this.responseTitle = 'Erro ao cadastrar marca!'
                        this.responseMessage = {
                            message: errors.response.data.message,
                            content: errors.response.data.erros
                        }
                        console.log(errors)
                    })
            },
            paginate(link) {
                if(link.url) {
                    this.pageUrl = link.url.split('?')[1]
                    this.loadItens()
                }
            }
        },
        mounted() {
            this.loadItens()
        }
    }
</script>