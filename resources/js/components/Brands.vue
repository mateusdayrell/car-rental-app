<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Busca de narcas</div>

                    <div class="card-body">
                        <div class="form-row">
                            <div class="col mb-3">

                                <input-container-component id="inputId" title="ID"></input-container-component>
                                
                                <label for="inputId" class="form-label">ID da marca</label>
                                <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID">
                                
                            </div>
                            <div class="col mb-3">
                                <label for="inputName" class="form-label">Nome da marca</label>
                                <input type="text" class="form-control" id="inputName" aria-describedby="nameHelp" placeholder="Nome">
                                <div id="nameHelp" class="form-text text-muted">Opicional. Informe o nome da marca</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>