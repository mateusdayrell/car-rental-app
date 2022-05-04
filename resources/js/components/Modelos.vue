<template>
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Search card -->
                <card-component title="Modelos">
                    <template v-slot:content>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component 
                                    id="inputId" 
                                    title="ID" 
                                    id-help="idHelp"
                                    helpText="">
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        id="inputId" 
                                        aria-describedby="idHelp" 
                                        placeholder="ID" 
                                        v-model="search.id" >
                                </input-container-component>
                            </div>
                            
                            <div class="col mb-3">
                                <input-container-component 
                                    id="inputName" 
                                    title="Modelo" 
                                    id-help="nameHelp" 
                                    helpText="">
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="inputName" aria-describedby="nameHelp" 
                                        placeholder="Modelo" v-model="search.name">
                                </input-container-component>
                            </div>
                            <div class="col mn-3">
                                <div class="form-group">
                                    <label class="form-label">Marca</label>
                                    <select 
                                        class="form-control" 
                                        id="inputBrand"
                                        aria-describedby="brandHelp"
                                        v-model="search.brand_id">
                                            <option value="" disabled selected>Selecione uma marca</option>
                                            <option v-for="b, key in brandList" :key="key" :value="b.id" >{{ b.name }}</option>
                                    </select>
                                </div>
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
                <div class="card mb-3">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Modelo</th>
                                <th>Imagem</th>
                                <th>Marca</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="model, key in models.data" :key="key">
                                <td>{{model.id}}</td>
                                <td>{{model.name}}</td>
                                <td>
                                    <img :src="'/storage/'+model.image" width="60" height="40">
                                </td>
                                <td>{{model.brand.name}}</td>
                                <td>
                                    <button 
                                        class="btn btn-outline-primary btn-sm" 
                                        data-toggle="modal" 
                                        data-target="#showModelModal"
                                        @click="setStore(model)" > Visualizar </button>

                                    <button 
                                        class="btn btn-outline-primary btn-sm" 
                                        data-toggle="modal" 
                                        data-target="#editModelModal"
                                        @click="setStore(model)" > Editar </button>

                                    <button 
                                        class="btn btn-outline-danger btn-sm" 
                                        data-toggle="modal" 
                                        data-target="#destroyModelModal"
                                        @click="setStore(model)" > Remover </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li 
                                        :class="link.active ? 'page-item active' : 'page-item'" 
                                        v-for="link, key in models.links" 
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
                                    data-target="#addModelModal"> Adicionar </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /List card -->

                <!-- ADD MODAL -->
                <modal-component id="addModelModal" title="Adicionar modelo">
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
                                id="NewName" 
                                title="Nome do modelo" 
                                id-help="NewNameHelp" 
                                helpText="Informe o nome do modelo">
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="NewName" 
                                    aria-describedby="NewNameHelp" 
                                    placeholder="Nome" 
                                    v-model="newModel.name">
                            </input-container-component>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <select 
                                class="form-control" 
                                id="inputBrand"
                                aria-describedby="brandHelp"
                                v-model="newModel.brand_id">
                                    <option value="" disabled selected>Selecione uma marca</option>
                                    <option v-for="b, key in brandList" :key="key" :value="b.id"> {{ b.name }}</option>
                            </select>
                        </div>
                        <div class="row">
                        <div class="form-group col">
                                <input-container-component 
                                    id="inputDoorQtd" 
                                    title="Nº de portas" 
                                    id-help="doorQtd"
                                    helpText="">
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        id="inputDoorQtd" 
                                        aria-describedby="doorQtd" 
                                        placeholder="Nº de portas" 
                                        v-model="newModel.door_qt" >
                                </input-container-component>
                            </div>

                        <div class="form-group col">
                                <input-container-component 
                                    id="inputSeaters" 
                                    title="Nº de lugares" 
                                    id-help="seaters"
                                    helpText="">
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        id="inputSeaters" 
                                        aria-describedby="seaters" 
                                        placeholder="Nº de lugares" 
                                        v-model="newModel.seaters" >
                                </input-container-component>
                            </div>
                            </div>

                        <div class="col">
                            <label class="form-label">Possui:</label>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    id="inputAbs"
                                    v-model="newModel.abs">
                                <label 
                                    class="form-check-label" 
                                    for="flexCheckDefault">ABS</label>
                            </div>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" value="" 
                                    id="inputAirBag"
                                    v-model="newModel.air_bag">
                                <label 
                                class="form-check-label" 
                                for="flexCheckChecked">Airbag</label>
                            </div>
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
                            data-dismiss="modal"> Fechar </button>
                        <button 
                            type="button" 
                            class="btn btn-primary" 
                            @click="save()"> Salvar </button>
                    </template>
                </modal-component>
                <!-- /ADD MODAL -->

                <!-- SHOW MODAL -->
                <modal-component id="showModelModal" title="Visualizar modelo">
                    <template v-slot:content>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component title="ID">
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        :value="$store.state.item.id" disabled>
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component title="Nome do modelo">
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        :value="$store.state.item.name" disabled>
                                </input-container-component>
                            </div>
                        </div>
                        <label for="">Imagem</label>
                        <input-container-component title="">
                            <img 
                                :src="'storage/'+$store.state.item.image" 
                                v-if="$store.state.item.image">
                        </input-container-component>
                        <input-container-component title="Marca">
                            <input 
                                type="text" 
                                class="form-control" 
                                :value="$store.state.item.brand.name" disabled>
                        </input-container-component>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component title="Nº de portas">
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        :value="$store.state.item.door_qt" disabled>
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component title="Nº de lugares">
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        :value="$store.state.item.seaters" disabled>
                                </input-container-component>
                            </div>
                        </div>
                         <div class="form-row">
                            <div class="col">
                                <label class="form-label">Adicionais:</label>
                                <div class="form-check"
                                    v-if="$store.state.item.abs == 1 ? true : false">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox"
                                        checked>
                                    <label 
                                        class="form-check-label" 
                                        for="flexCheckDefault">ABS</label>
                                </div>
                                <div class="form-check"
                                    v-if="$store.state.item.air_bag == 1 ? true : false">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox"
                                        checked>
                                    <label 
                                    class="form-check-label" 
                                    for="flexCheckChecked">Airbag</label>
                                </div>
                            </div>
                            <div class="col mt-1">
                                <input-container-component title="Data de criação">
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        :value="formatDateTime($store.state.item.created_at)" disabled>
                                </input-container-component>
                            </div>
                         </div>
                    </template>

                    <template v-slot:footer>
                        <button 
                            type="button" 
                            class="btn btn-secondary" 
                            data-dismiss="modal"> Fechar </button>
                    </template>
                </modal-component>
                <!-- /SHOW MODAL -->

                <!-- EDIT MODAL -->
                <modal-component id="editModelModal" title="Editar modelo">
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
                    <template v-slot:content>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component title="ID">
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        :value="$store.state.item.id" disabled>
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component 
                                id="updateName" 
                                title="Nome do modelo" 
                                id-help="updateNameHelp" 
                                helpText="">
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="updateName" 
                                    aria-describedby="updateNameHelp" 
                                    placeholder="Nome" 
                                    v-model="$store.state.item.name">
                            </input-container-component>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <select 
                                class="form-control" 
                                id="updateInputBrand"
                                aria-describedby="brandHelp"
                                v-model="$store.state.item.brand_id">
                                    <option :value="$store.state.item.brand_id" selected>{{$store.state.item.brand.name}}</option>
                                    <option v-for="b, key in brandList" :key="key" :value="b.id"> {{ b.name }}</option>
                            </select>
                        </div>
                        <div class="row">
                        <div class="form-group col">
                                <input-container-component 
                                    id="updateInputDoorQtd" 
                                    title="Nº de portas" 
                                    id-help="doorQtd"
                                    helpText="">
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        id="updateInputDoorQtd" 
                                        aria-describedby="doorQtd" 
                                        placeholder="Nº de portas" 
                                        v-model="$store.state.item.door_qt" >
                                </input-container-component>
                            </div>

                        <div class="form-group col">
                                <input-container-component 
                                    id="updateInputSeaters" 
                                    title="Nº de lugares" 
                                    id-help="seaters"
                                    helpText="">
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        id="updateInputSeaters" 
                                        aria-describedby="seaters" 
                                        placeholder="Nº de lugares" 
                                        v-model="$store.state.item.seaters" >
                                </input-container-component>
                            </div>
                            </div>

                             <div class="form-row">
                            <div class="col">
                                <label class="form-label">Adicionais:</label>
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        id="updateInputAbs"
                                        v-model="$store.state.item.abs">
                                    <label 
                                        class="form-check-label" 
                                        for="flexCheckDefault">ABS</label>
                                </div>
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" value="" 
                                        id="updateInputAirBag"
                                        v-model="$store.state.item.air_bag">
                                    <label 
                                    class="form-check-label" 
                                    for="flexCheckChecked">Airbag</label>
                                </div>
                            </div>
                            <div class="col mr-8">
                                <input-container-component 
                                id="updateImage" 
                                title="Imagem" 
                                id-help="updateImageHelp"
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
                         </div>
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

                <!-- DESTROY MODAL-->
                <modal-component id="destroyModelModal" title="Remover modelo">
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
                        <input-container-component title="Modelo">
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
                
            </div>
        </div>


    </div>
</template>

<script>
    export default {
        data() {
            return {
                baseUrl: 'http://localhost:8000/api/v1/modelo',
                pageUrl: '',
                filterUrl: '',

                models:{ data: [] },
                brandList:{ data: [] },
                newModel: {
                    id: '', 
                    name: '', 
                    brand_id: '', 
                    door_qt: '',
                    image: [] ,
                    seaters: '', 
                    air_bag: 0, 
                    abs: 0
                },
                search: {id: '', brand_id: '', name: ''},

                modelImage: [],
                modelAbs: '',
                modelAirBag: '',

                responseStatus: '',
                responseMessage: {},
                responseTitle: '',
            }
        },
        methods: {
            getBrands() {
                    let url = 'http://localhost:8000/api/v1/brand-all'

                    axios.get(url)
                        .then(response => {
                            this.brandList = response.data
                        })
                        .catch(errors => { console.log(errors) })
            },
            loadItens() {
                    let url = this.baseUrl + '?' + this.pageUrl + this.filterUrl
                    
                    axios.get(url)
                        .then(response => {
                            this.models = response.data
                        })
                        .catch(errors => { console.log(errors) })
            },
            loadImage(e) {
                try{
                    this.modelImage = e.target.files
                } catch (errors) {
                    console.log(errors)
                }
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
            save() {
                this.getCheckbox('save')

                let formData = new FormData()
                formData.append('name', this.newModel.name)
                formData.append('brand_id', this.newModel.brand_id)
                formData.append('image', this.modelImage[0])
                formData.append('door_qt', this.newModel.door_qt)
                formData.append('seaters', this.newModel.seaters)
                formData.append('air_bag', this.newModel.air_bag)
                formData.append('abs', this.newModel.abs)

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }

                axios.post(this.baseUrl, formData, config)
                    .then(response => {
                        this.responseStatus = 'success'
                        this.responseTitle = 'Modelo cadastrada com sucesso!'
                        this.responseMessage = {
                            message: 'ID do registro: ' + response.data.id
                        }
                        this.loadItens()
                    })
                    .catch(errors => {
                        this.responseStatus = 'error'
                        this.responseTitle = 'Erro ao cadastrar modelo!'
                        this.responseMessage = {
                            message: errors.response.data.message,
                            content: errors.response.data.erros
                        }
                    })
            },
            update() {
                this.getCheckbox('update')

                let url = this.baseUrl + '/' + this.$store.state.item.id

                let formData = new FormData()
                formData.append('_method', 'patch')
                formData.append('name', this.$store.state.item.name)
                formData.append('brand_id', this.$store.state.item.brand_id)
                formData.append('door_qt', this.$store.state.item.door_qt)
                formData.append('seaters', this.$store.state.item.seaters)
                formData.append('air_bag', this.modelAirBag)
                formData.append('abs', this.modelAbs)

                // Verify image input
                if(this.modelImage[0]) {
                    formData.append('image', this.modelImage[0])
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
            paginate(link) {
                if(link.url) {
                    this.pageUrl = link.url.split('?')[1]
                    this.loadItens()
                }
            },
            getCheckbox(func) {
                if(func == 'save') {
                    this.newModel.air_bag == false ? this.newModel.air_bag = 0 : this.newModel.air_bag = 1
                    this.newModel.abs == false ? this.newModel.abs = 0 : this.newModel.abs = 1
                } else {
                    this.$store.state.item.air_bag == false ? this.modelAirBag = 0 : this.modelAirBag = 1
                    this.$store.state.item.abs == false ? this.modelAbs = 0 : this.modelAbs = 1
                }
                return
            },
            setStore(model) {
                this.$store.state.transaction.status = ''
                this.$store.state.transaction.message = ''
                this.$store.state.transaction.data = ''
                this.$store.state.item = model
            },
            formatDateTime(d) {
                if(!d) {
                    return ''
                }
                d = d.split('T')
                let date = d[0]
                let time = d[1]

                date = date.split('-')
                date = date[2] + '/' + date[1] + '/' + date[0]

                time = time.split('.')[0]

                return (date + ' ' + time)
            },
        },
        mounted() {
            this.getBrands()
            this.loadItens()
        }
    }
</script>