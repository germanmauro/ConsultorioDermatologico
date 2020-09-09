//Desloguear
function desloguear()
{
    $.ajax
        ({
            url: 'Accion/desloguear.php',
            // data: {mesa: Mesa},
            type: 'post',
            cache: false,
                success: function (r) {
                if (r != "") {
                    alert(r);
                    //return r;
                }
                else {
                    location.reload();
                    // paginaPrincipal('login.php')
                }
            }
        });
}
function logear(user,pass) {
    $.ajax
        ({
            url: 'login.php',
            data: {user: user,pass:pass,registro:'ok'},
            type: 'post',
            cache: false,
            success: function (r) {
                    location.reload();
                }
        });
}

//Nuevo agregar Producto la pedido
function cargarProducto(id) {
    var idcant = id + 'cantidad';
    var cantidad = document.getElementById(idcant).value;
    var btn = '#'+id;
    
        if (parseFloat(cantidad) > 0 && parseFloat(cantidad)<9999)
        {
            $(btn).attr("disabled", true);
            $.ajax
                ({
                    url: 'Accion/cargarProducto.php',
                    data: { id: id,cantidad:cantidad },
                    type: 'post',
                    cache: false,
                    success: function (r) {
                        if (r != "") {
                            alert(r);
                            //return r;
                        }
                        else {
                            // location.reload();
                            recargaPedido();
                            //Alerta
                            swal("Producto agregado a la canasta!", {
                                buttons: false,
                                icon: "success",
                                timer: 2500,
                            });
                            $(btn).attr("disabled", false);
                        }
                    }
                });
            }
            else {
            swal("Debe ingresar un mayor a 0", {
                buttons: false,
                icon: "error",
                timer: 3000,
            });
            }

}

//Borrar Producto del pedido
function borrarProducto(id,modificar=false) {
    swal("¿Desea eliminar el producto?", {
        icon:"warning",
        buttons: {
           

            catch: {
                text: "SI",
                value: "catch",
            },
            cancel: "NO",
        },
    })
        .then((value) => {
            switch (value) {

                case "catch":
                    $.ajax
                        ({
                            url: 'Accion/borrarProducto.php',
                            data: { id: id },
                            type: 'post',
                            cache: false,
                            success: function (r) {
                                if (r != "") {
                                    alert(r);
                                    //return r;
                                }
                                else {
                                    // location.reload();
                                    if(modificar)
                                    {
                                        paginaPrincipal('pedidodetallemodificar.php');
                                    }
                                    else {
                                        paginaPrincipal('pedidodetalle.php');
                                    }
                                    
                                }
                            }
                        });
                    break;

            }
        });    
}

//Generar Pedido
function generarPedido(fecha) {
    swal("¿Desea generar el pedido?", {
        icon: "warning",
        buttons: {
            

            catch: {
                text: "SÍ",
                value: "catch",
            },
            cancel: "NO",

        },
    })
        .then((value) => {
            switch (value) {

                case "catch":
                    $.ajax
                        ({
                            url: 'Accion/generarPedido.php',
                            type: 'post',
                            data: { fecha: fecha },
                            cache: false,
                            success: function (r) {
                                if (r != "") {
                                    swal(r, {
                                        buttons: false,
                                        icon: "error",
                                        timer: 3000,
                                    });
                                    //return r;
                                }
                                else {
                                    paginaPrincipal('pedido.php');
                                    swal("Pedido generado con éxito!","El mismo será entregado la fecha indicada",
                                     {
                                        buttons: false,
                                        icon: "success",
                                        timer: 4000,
                                    });
                                    
                                }
                            }
                        });
                    break;

            }
        });
}

//Modificar Pedido
function confirmarModificacion() {
    swal("¿Desea confirmar las modificaciones del pedido?", {
        icon: "warning",
        buttons: {
            

            catch: {
                text: "SÍ",
                value: "catch",
            },
            cancel: "NO",

        },
    })
        .then((value) => {
            switch (value) {

                case "catch":
                    $.ajax
                        ({
                            url: 'Accion/confirmarModificacion.php',
                            type: 'post',
                            cache: false,
                            success: function (r) {
                                if (r != "") {
                                    swal(r, {
                                        buttons: false,
                                        icon: "error",
                                        timer: 3000,
                                    });
                                    //return r;
                                }
                                else {
                                    paginaPrincipal('pedido.php');
                                    swal("Pedido modificado con éxito!","El mismo será entregado la fecha indicada",
                                     {
                                        buttons: false,
                                        icon: "success",
                                        timer: 4000,
                                    });
                                    
                                }
                            }
                        });
                    break;

            }
        });
}

//Cargar detalle de pedido
function cargadetalle(id) {
    param = { id: id };
    subPagina('mispedidosdetalle.php', param);
}

//Cargar detalle de pedido
function modificarpedido(id) {
    $.ajax
        ({
            url: 'Accion/modificarPedido.php',
            data: { id: id },
            type: 'post',
            cache: false,
            success: function (r) {
               paginaPrincipal('pedidodetallemodificar.php');
            }
        });
}