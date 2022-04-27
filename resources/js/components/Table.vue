<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th v-for="t, key in titles" :key="key" scope="col">{{t.title}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, key in dataFilter" :key="key">
                    <td v-for="item, key in obj" :key="key">
                        <span v-if="titles[key].type === 'text'"> {{ item }}</span>
                        <span v-if="titles[key].type === 'date'">{{ item }}</span>
                        <span v-if="titles[key].type === 'image'">
                            <img :src="'/storage/'+item" width="30" height="30">
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</template>

<script>
    export default {
        props: ['data', 'titles'],
        computed: {
            dataFilter() {
                console.log(this.data)
                let fields = Object.keys(this.titles)
                let dataArr = []

                this.data.map((item, key) => {
                    let filteredItens = {}
                    
                    fields.forEach(field => {
                        filteredItens[field] = item[field] //adding dynamic attributes to an object
                    })
                    dataArr.push(filteredItens)
                })

                return dataArr
            }
        }
    }
</script>
