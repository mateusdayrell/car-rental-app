<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Search card -->
                <card-component title="Busca de marcas">
                    <template v-slot:content>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component 
                                    id="inputId" 
                                    title="ID" 
                                    id-help="idHelp"
                                    helpText="Opicional. Informe o ID da marca">
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        id="inputId" 
                                        aria-describedby="idHelp" 
                                        placeholder="ID" 
                                        v-model="search.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">

                                <input-container-component 
                                    id="inputName" 
                                    title="Nome" 
                                    id-help="nameHelp" 
                                    helpText="Opicional. Informe o nome da marca">
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="inputName" aria-describedby="nameHelp" 
                                        placeholder="Nome" v-model="search.name">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                        
                    <template v-slot:footer>
                        <button 
                            type="submit" 
                            class="btn btn-primary btn-sm float-right" 
                            @click="searchItens()"> Pesquisar </button>
                    </template>
                </card-component>
                <!-- /Search card -->


                <!-- List card -->
                <card-component title="Relação de marcas">
                    <template v-slot:content>
                        <table-component 
                            :data="brands.data" 
                            :visualize="{ show: true, dataToggle: 'modal', dataTarget:'#showBrandModal'}"
                            :edit="{ show: true, dataToggle: 'modal', dataTarget:'#editBrandModal'}"
                            :destroy="{ show: true, dataToggle: 'modal', dataTarget:'#destroyBrandModal'}"
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
                                        v-for="link, key in brands.links" 
                                        :key="key" 
                                        @click="paginate(link)">
                                            <a class="page-link" v-html="link.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button 
                                    type="submit" 
                                    class="btn btn-primary btn-sm float-right" 
                                    data-toggle="modal" 
                                    data-target="#addBrandModal"> Adicionar </button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- /List card -->
            </div>
        </div>


        <!-- ADD MODAL -->
        <modal-component id="addBrandModal" title="Adicionar marca">
            <template v-slot:alerts>
                <alert-component 
                    type="success" 
                    :title="responseTitle" 
                    :alertMessage="responseMessage" 
                    v-if="responseStatus === 'success'"/>
                <alert-component 
                type="danger" 
                :title="responseTitle" 
                :alertMessage="responseMessage" 
                v-if="responseStatus === 'error'"/>
            </template>
            <template v-slot:content>
                <div class="form-group">
                    <input-container-component 
                        id="newName" 
                        title="Nome" 
                        id-help="NewNameHelp" 
                        helpText="Informe o nome da marca">
                        <input 
                            type="text" 
                            class="form-control" 
                            id="newName" 
                            aria-describedby="NewNameHelp" 
                            placeholder="Nome" 
                            v-model="brandName">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component 
                        id="newImage" 
                        title="Imagem" 
                        id-help="newImageHelp"
                        helpText="Selecione uma imagem PNG">
                        <input 
                            type="file" 
                            class="form-control-file" 
                            id="newImage" 
                            aria-describedby="newImageHelp"
                            placeholder="Imagem" 
                            @change="loadImage($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:footer>
                <button 
                    type="button" 
                    class="btn btn-secondary" 
                    data-dismiss="modal"
                    @click="clearValues()"> Fechar </button>
                <button 
                    type="button" 
                    class="btn btn-primary" 
                    @click="save()"> Salvar </button>
            </template>
        </modal-component>
        <!-- /ADD MODAL -->


        <!-- SHOW MODAL -->
        <modal-component id="showBrandModal" title="Visualizar marca">
            <template v-slot:alerts>
                <alert-component 
                    type="success" 
                    :title="responseTitle" 
                    :message="responseMessage" 
                    v-if="responseStatus === 'success'"/>
                <alert-component 
                    type="danger" 
                    :title="responseTitle" 
                    :message="responseMessage" 
                    v-if="responseStatus === 'error'"/>
            </template>
            <template v-slot:content>
                <input-container-component title="ID">
                    <input 
                        type="text" 
                        class="form-control" 
                        :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component title="Nome">
                    <input 
                        type="text" 
                        class="form-control" 
                        :value="$store.state.item.name" disabled>
                </input-container-component>
                <input-container-component title="Logo">
                    <img 
                        :src="'storage/'+$store.state.item.image" 
                        v-if="$store.state.item.image">
                </input-container-component>
                <input-container-component title="Data de criação">
                    <input 
                        type="text" 
                        class="form-control" 
                        :value="$store.state.item.created_at" disabled>
                </input-container-component>
            </template>

            <template v-slot:footer>
                <button 
                    type="button" 
                    class="btn btn-secondary" 
                    data-dismiss="modal"> Fechar </button>
            </template>
        </modal-component>
        <!-- /SHOW MODAL -->
        

        <!-- DESTROY MODAL-->
        <modal-component id="destroyBrandModal" title="Remover marca">
            <template v-slot:alerts>
                <alert-component 
                    type="success" 
                    title="Remoção realizada com sucesso" 
                    :alertMessage="$store.state.transaction" 
                    v-if="$store.state.transaction.status == 'success'"/>
                <alert-component 
                    type="danger" 
                    title="Erro ao remover registro" 
                    :alertMessage="$store.state.transaction" 
                    v-if="$store.state.transaction.status == 'error'"/>
            </template>
            <template 
                v-slot:content 
                v-if="$store.state.transaction.status != 'success'">
                <input-container-component title="ID">
                    <input 
                        type="text" 
                        class="form-control" 
                        :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component title="Nome">
                    <input 
                        type="text" 
                        class="form-control" 
                        :value="$store.state.item.name" disabled>
                </input-container-component>
            </template>

            <template v-slot:footer>
                <button 
                    type="button" 
                    class="btn btn-secondary" 
                    data-dismiss="modal">Fechar</button>
                <button 
                    type="button" 
                    class="btn btn-danger" 
                    v-if="$store.state.transaction.status != 'success'" 
                    @click="destroy()">Remover</button>
            </template>
        </modal-component>
        <!-- /DESTROY MODAL -->


        <!-- EDIT MODAL -->
        <modal-component id="editBrandModal" title="Editar marca">
            <template v-slot:alerts>
                <alert-component 
                    type="success" 
                    title="Atualização realizada com sucesso" 
                    :alertMessage="$store.state.transaction" 
                    v-if="$store.state.transaction.status == 'success'"/>
                <alert-component 
                    type="danger" 
                    title="Erro ao atualizar registro" 
                    :alertMessage="$store.state.transaction" 
                    v-if="$store.state.transaction.status == 'error'"/>
            </template>
            <template v-slot:content >
                <div class="form-group">
                    <input-container-component 
                        id="updateName" 
                        title="Nome" 
                        id-help="updateNameHelp" 
                        helpText="Informe o nome da marca">
                            <input 
                                type="text" 
                                class="form-control" 
                                id="updateName" 
                                aria-describedby="updateNameHelp" 
                                placeholder="Nome" 
                                v-model="$store.state.item.name">
                    </input-container-component>
                </div>
                <div class="form-group">
                    <input-container-component 
                        id="updateImage" 
                        title="Imagem" 
                        d-help="updateImageHelp" 
                        helpText="Selecione uma imagem PNG">
                            <input 
                                type="file" 
                                class="form-control-file" 
                                id="updateImage" 
                                aria-describedby="updateImageHelp" 
                                placeholder="Imagem" 
                                @change="loadImage($event)">
                    </input-container-component>
                </div>
                {{ $store.state.item }}
            </template>

            <template v-slot:footer>
                <button 
                    type="button" 
                    class="btn btn-secondary" 
                    data-dismiss="modal"> Fechar </button>
                <button 
                    type="button" 
                    class="btn btn-danger"
                    @click="update()"> Atualizar </button>
            </template>
        </modal-component>
        <!-- /EDIT MODAL -->

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
        methods: {
            loadItens() {
                let url = this.baseUrl + '?' + this.pageUrl + this.filterUrl
                
                axios.get(url)
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
                        filter += key + ':like:' + this.search[key]
                    }
                }

                if(filter != '') {
                    this.pageUrl = 1
                    this.filterUrl = '&filter=' + filter
                } else {
                    this.filterUrl = ''
                }

                this.loadItens()
            },
            paginate(link) {
                if(link.url) {
                    this.pageUrl = link.url.split('?')[1]
                    this.loadItens()
                }
            },
            save() {
                let formData = new FormData()
                formData.append('name', this.brandName)
                formData.append('image', this.brandImage[0])

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }

                axios.post(this.baseUrl, formData, config)
                    .then(response => {
                        this.responseStatus = 'success'
                        this.responseTitle = 'Marca cadastrada com sucesso!'
                        this.responseMessage = {
                            message: 'ID do registro: ' + response.data.id
                        }
                        console.log('Mensagem',this.responseMessage)
                        // this.loadItens()
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
            update() {
                let url = this.baseUrl + '/' + this.$store.state.item.id

                let formData = new FormData()
                formData.append('_method', 'patch')
                formData.append('name', this.$store.state.item.name)

                // Verify image input
                if(this.brandImage[0]) {
                    formData.append('image', this.brandImage[0])
                }

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                }

                axios.post(url, formData, config)
                    .then(response => {
                        updateImage.value = ''
                        this.$store.state.transaction.status = 'success'
                        this.$store.state.transaction.message = 'Registro atualizado com sucesso!'
                        this.loadItens()
                    })
                    .catch(errors => {
                        this.$store.state.transaction.status = 'error'
                        this.$store.state.transaction.message = errors.response.data.message
                        this.$store.state.transaction.data = errors.response.data.errors
                    })
            },
            destroy() {
                let conf = confirm('Tem certeza que deseja remover esse registro?')
                if (!conf) return false

                let url = `${this.baseUrl}/${this.$store.state.item.id}`

                let formData = new FormData()
                formData.append('_method', 'delete')
                
                axios.post(url, formData)
                    .then(response => {
                        this.$store.state.transaction.status = 'success'
                        this.$store.state.transaction.message = response.data.msg
                        this.loadItens()
                    })
                    .catch(errors => {
                        this.$store.state.transaction.status = 'error'
                        this.$store.state.transaction.message = errors.response.data.erro
                    })
            },
            clearValues() {
                this.brandName = '';
                this.brandImage = [];
                newImage.value = '';
            }
        },
        mounted() {
            this.loadItens()
        }
    }
</script>