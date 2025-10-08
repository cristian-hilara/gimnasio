$("#usuario_search").autocomplete({
    source: function(request, response) {
        $.ajax({
            url: "/buscar-usuarios",
            dataType: "json",
            data: {
                term: request.term
            },
            success: function(data) {
                response(data);
            }
        });
    },
    minLength: 2,
    select: function(event, ui) {
        $("#usuario_id").val(ui.item.id); // guardamos id
        $("#usuario_search").val(ui.item.value); // mostramos nombre y apellido
        return false; // evita que el valor del input se sobrescriba automáticamente
    },
    change: function(event, ui) {
        if (!ui.item) {
            $("#usuario_id").val('');
            $("#usuario_search").val('');
        }
    }
});
