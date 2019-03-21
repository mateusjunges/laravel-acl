$(document).ready(function () {
    var dataTable = $(".dataTable tbody");
    /**
     * Função para remover um registro no banco de dados.
     */
    dataTable.on('click', 'button.delete', function () {
        let type = $(this).data('type');
        let token = $(this).val();
        let name = $(this).data('name');
        let route = $(this).data('route');
        let id = $(this).data('id');
        let gender = $(this).data('gender');
        let permission = $(this).data('permissionid');
        console.log(id);
        var tr = $(this).closest('tr');

        swal(`Deseja realmente excluir ${gender} ${type} ${name}?`,{
            icon: 'warning',
            buttons: true,
        }).then((response) => {
            if (response === true){
                $.ajax({
                    method: 'post',
                    url: `/${route}/${id}/${permission}`,
                    async: false,
                    data: {
                        '_token': token,
                        '_method': 'delete',
                    },
                    success: function (response) {
                        swal({
                            icon: response.icon,
                            title: response.title,
                            text: response.text,
                            timer: response.timer,
                        });
                        if (response.code === 200){
                            tr.fadeOut(400, function () {
                                tr.remove();
                            });
                        }
                    }
                });
            }
        });
    });

    /**
     * Função para restaurar um registro apagado no banco de dados
     */
    dataTable.on('click', 'button.restore', function () {
        let type = $(this).data('type');
        let token = $(this).val();
        let name = $(this).data('name');
        let route = $(this).data('route');
        let id = $(this).data('id');
        let gender = $(this).data('gender');

        var tr = $(this).closest('tr');

        swal(`Deseja realmente restaurar ${gender} ${type} ${name}?`,{
            icon: 'warning',
            buttons: true,
        }).then((response) => {
            if (response === true){
                $.ajax({
                    method: 'post',
                    url: `/${route}/${id}`,
                    async: false,
                    data: {
                        '_token': token,
                        '_method': 'put',
                    },
                    success: function (response) {
                        swal({
                            icon: response.icon,
                            title: response.title,
                            text: response.text,
                            timer: response.timer,
                        });
                        if (response.code === 200){
                            tr.fadeOut(400, function () {
                                tr.remove();
                            });
                        }
                    }
                });
            }
        });
    });

    /**
     * Função para deletar permantemente um registro do banco de dados
     */
    dataTable.on('click', 'button.permanetly-delete', function () {
        let type = $(this).data('type');
        let token = $(this).val();
        let name = $(this).data('name');
        let route = $(this).data('route');
        let id = $(this).data('id');
        let gender = $(this).data('gender');

        var tr = $(this).closest('tr');

        swal(`Deseja realmente excluir ${gender} ${type} ${name} permanentemente?`,{
            icon: 'warning',
            buttons: true,
        }).then((response) => {
            if (response === true){
                $.ajax({
                    method: 'post',
                    url: `/${route}/${id}`,
                    async: false,
                    data: {
                        '_token': token,
                        '_method': 'delete',
                    },
                    success: function (response) {
                        swal({
                            icon: response.icon,
                            title: response.title,
                            text: response.text,
                            timer: response.timer,
                        });
                        if (response.code === 200){
                            tr.fadeOut(400, function () {
                                tr.remove();
                            });
                        }
                    }
                });
            }
        });
    });
});