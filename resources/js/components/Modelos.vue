<template>
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Search card -->
                <card-component title="Busca de modelos">
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
                                        v-model="search.branId">
                                            <option value="" disabled selected>Selecione uma marca</option>
                                            <option v-for="b, key in brands" :key="key" :value="b.id"> {{ b.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col mt-4">
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        id="inputAbs"
                                        v-model="search.abs">
                                    <label 
                                        class="form-check-label" 
                                        for="flexCheckDefault">ABS</label>
                                </div>
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" value="" 
                                        id="inputAirBag"
                                        v-model="search.airBag">
                                    <label 
                                    class="form-check-label" 
                                    for="flexCheckChecked">Air-Bag</label>
                                </div>
                            </div>
                        </div>
                    </template>
                        
                    <template v-slot:footer>
                        <button 
                            type="submit" 
                            class="btn btn-primary btn-sm float-right" 
                            @click="getBrands()"> Pesquisar </button>
                    </template>
                </card-component>
                <!-- /Search card -->


                <!-- List card -->
                <card-component title="Relação de modelos">
                    <!-- <template v-slot:content>
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
                    </template> -->
                    <template v-slot:footer>
                        <div class="row">
                            <!-- <div class="col-10">
                                <paginate-component>
                                    <li 
                                        :class="link.active ? 'page-item active' : 'page-item'" 
                                        v-for="link, key in brands.links" 
                                        :key="key" 
                                        @click="paginate(link)">
                                            <a class="page-link" v-html="link.label"></a>
                                    </li>
                                </paginate-component>
                            </div> -->
                            <div class="col">
                                <button 
                                    type="submit" 
                                    class="btn btn-primary btn-sm float-right" 
                                    data-toggle="modal" 
                                    data-target="#addModelModal"> Adicionar </button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- /List card -->

                <!-- ADD MODAL -->
                <modal-component id="addModelModal" title="Adicionar modelo">
                    <!-- <template v-slot:alerts>
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
                    </template> -->
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
                                    <option v-for="b, key in brands" :key="key" :value="b.id"> {{ b.name }}</option>
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
                                    title="Nº de portas" 
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

                        <div class="col mt-4">
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
                                        {{newModel.abs}}
                                </div>
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" value="" 
                                        id="inputAirBag"
                                        v-model="newModel.air_bag">
                                    <label 
                                    class="form-check-label" 
                                    for="flexCheckChecked">Air-Bag</label>
                                    {{newModel.air_bag}}
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
                            data-dismiss="modal"
                            @click="clearValues()"> Fechar </button>
                        <button 
                            type="button" 
                            class="btn btn-primary" 
                            @click="save()"> Salvar </button>
                    </template>
                </modal-component>
                <!-- /ADD MODAL -->
                
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
                brands:{ data: [] },
                newModel: {
                    id: '', 
                    name: '', 
                    brand_id: '', 
                    image: [], 
                    door_qt: '', 
                    seaters: '', 
                    air_bag: false, 
                    abs: false
                },
                search: {id: '', brand_id: '', name: '', airBag: '', abs: ''}
            }
        },
        methods: {
            getBrands() {
                let url = 'http://localhost:8000/api/v1/brand-all'

                axios.get(url)
                    .then(response => {
                        this.brands = response.data
                        console.log(this.brands)
                    })
                    .catch(errors => { console.log(errors) })
            },
            loadImage(e) {
                this.newModel.image = e.target.files
                console.log('IMAGE: ', this.newModel.image)
                console.log('NEW: ', this.newModel)
            },
            save() {
                console.log(this.newModel)
                let formData = new FormData()
                formData.append('name', this.newModel.name)
                formData.append('brand_id', this.newModel.brand_id)
                formData.append('image', this.newModel.image[0])
                formData.append('door_qt', this.newModel.door_qt)
                formData.append('seaters', this.newModel.seaters)
                formData.append('air_bag', this.newModel.air_bag)
                formData.append('abs', this.newModel.abs)

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                }

                console.log('FORMDATA: ', formData)

                axios.post(this.baseUrl, formData, config)
                    .then(response => {
                        // this.responseStatus = 'success'
                        // this.responseTitle = 'Marca cadastrada com sucesso!'
                        // this.responseMessage = {
                        //     message: 'ID do registro: ' + response.data.id
                        // }
                        console.log(response)
                        // this.loadItens()
                    })
                    .catch(errors => {
                        // this.responseStatus = 'error'
                        // this.responseTitle = 'Erro ao cadastrar marca!'
                        // this.responseMessage = {
                        //     message: errors.response.data.message,
                        //     content: errors.response.data.erros
                        // }
                        console.log(errors)
                    })
            }

        },
        mounted() {
            this.getBrands()
        }
    }
</script>