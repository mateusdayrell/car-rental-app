<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th v-for="t, key in titles" :key="key" scope="col">{{t.title}}</th>
                    <th v-if="visualize.show || edit.show || destroy.show">Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, key in dataFilter" :key="key">
                    <td v-for="item, key in obj" :key="key">
                        <span v-if="titles[key].type === 'text'"> {{ item }}</span>
                        <span v-if="titles[key].type === 'date'">{{ item | formatDateTimeGlobal }}</span>
                        <span v-if="titles[key].type === 'image'">
                            <img :src="'/storage/'+item" width="30" height="30">
                        </span>
                    </td>
                    <td v-if="visualize.show || edit.show || destroy.show">
                        <button 
                            v-if="visualize.show" 
                            class="btn btn-outline-primary btn-sm" 
                            :data-toggle="visualize.dataToggle" 
                            :data-target="visualize.dataTarget"
                            @click="setStore(obj)" > Visualizar </button>

                        <button 
                            v-if="edit.show" 
                            class="btn btn-outline-primary btn-sm" 
                            :data-toggle="edit.dataToggle" 
                            :data-target="edit.dataTarget"
                            @click="setStore(obj)" > Editar </button>

                        <button 
                            v-if="destroy.show" 
                            class="btn btn-outline-danger btn-sm" 
                            :data-toggle="destroy.dataToggle" 
                            :data-target="destroy.dataTarget"
                            @click="setStore(obj)" > Remover </button>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</template>

<script>
    export default {
        props: ['data', 'titles', 'visualize', 'edit', 'destroy'],
        methods: {
            setStore(obj) {
                this.$store.state.transaction.status = ''
                this.$store.state.transaction.message = ''
                this.$store.state.transaction.data = ''
                this.$store.state.item = obj
            }
        },
        computed: {
            dataFilter() {
                let fields = Object.keys(this.titles)
                let dataArr = []
                
                this.data.map((item, key) => {
                    let filteredItens = {}
                    
                    fields.forEach(field => {
                        if(field == 'brand'){
                            filteredItens[field] = item[field].name
                        } else {
                            filteredItens[field] = item[field] //adding dynamic attributes to an object
                        }
                    })
                    dataArr.push(filteredItens)
                })

                return dataArr
            }
        },
    }
</script>
