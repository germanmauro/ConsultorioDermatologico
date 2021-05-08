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

function cargaMedico(id) {
    $.ajax
        ({
            url: 'Accion/cargaMedico.php',
            data: { id: id },
            type: 'post',
            cache: false,
            success: function (r) {
               paginaPrincipal('turnosduracion.php');
            }
        });
}

function cargaDuracion(duracion) {
    $.ajax
        ({
            url: 'Accion/cargaDuracion.php',
            data: { duracion: duracion },
            type: 'post',
            cache: false,
            success: function (r) {
                if (r != "") {
                    swal(r, {
                        buttons: false,
                        icon: "error",
                        timer: 3000,
                    });
                }
                else {
                    paginaPrincipal('turnosfecha.php');
                }
            }
        });
}
function cargaTratamiento(id,observaciones) {
    $.ajax
        ({
            url: 'Accion/cargaTratamiento.php',
            data: { id: id, observaciones: observaciones },
            type: 'post',
            cache: false,
            success: function (r) {
               paginaPrincipal('turnospaciente.php');
            }
        });
}

function setFecha(fecha) {
    $.ajax
        ({
            url: 'Accion/cargaFecha.php',
            data: { fecha: fecha },
            type: 'post',
            cache: false,
            success: function (r) {
                paginaPrincipal('turnosfecha.php');
            }
        });
}

function setHora(hor) {
    $.ajax
        ({
            url: 'Accion/cargaHora.php',
            data: { hora: hor },
            type: 'post',
            cache: false,
            success: function (r) {
                if (r != "") {
                    swal(r, {
                        buttons: false,
                        icon: "error",
                        timer: 3000,
                    });
                }
                else {
                    paginaPrincipal('turnostratamiento.php');
                }
                
            }
        });
}
// Carga de pacientes para turnos
function cargapaciente(id) {
    param = { Id: id };
    paginaPrincipal('turnospaciente.php', param);
}
// Carga de pacientes para ventas
function cargapacienteventa(id) {
    param = { Id: id };
    paginaPrincipal('ventapaciente.php', param);
}
// Carga de pacientes para tratamientos
function cargapacientetratamiento(id) {
    param = { Id: id };
    paginaPrincipal('tratamientopaciente.php', param);
}

//Borrar Producto del pedido
function eliminarTurno(id) {
    swal("¿Desea cancelar el turno?", {
        icon: "warning",
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
                            url: 'Accion/eliminarTurno.php',
                            data: { id: id },
                            type: 'post',
                            cache: false,
                            success: function (r) {
                                if (r != "") {
                                    alert(r);
                                    //return r;
                                }
                                else {
                                        paginaPrincipal('turnosfecha.php');
                                    }
                                }
                        });
                    break;

            }
        });
}

// Ventas
function nuevaVenta() {
    $.ajax
        ({
            url: 'Accion/nuevaVenta.php',
            type: 'post',
            cache: false,
            success: function (r) {
                paginaPrincipal('ventafecha.php');
            }
        });
}
function cargaFechaVenta(fecha, factura, observaciones) {
    $.ajax
        ({
            url: 'Accion/cargaFechaVenta.php',
            data: { fecha: fecha, factura: factura, observaciones: observaciones },
            type: 'post',
            cache: false,
            success: function (r) {
                paginaPrincipal('ventapaciente.php');
            }
        });
}

//Nuevo agregar Producto la pedido
function cargaItemProducto(id, cantidad) {

    $.ajax
        ({
            url: 'Accion/cargaItemProducto.php',
            data: { id: id, cantidad: cantidad },
            type: 'post',
            cache: false,
            success: function (r) {
                // location.reload();
                subPagina('ventadetalle.php');
            }
        });
}

//Borrar Producto del pedido
function borrarItemProducto(id) {
    swal("¿Desea eliminar el producto?", {
        icon: "warning",
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
                            url: 'Accion/borrarItemProducto.php',
                            data: { id: id },
                            type: 'post',
                            cache: false,
                            success: function (r) {

                                // location.reload();
                                subPagina('ventadetalle.php')

                            }
                        });
                    break;

            }
        });
}

function cargaFormaPagoVenta(formapago) {
    $.ajax
        ({
            url: 'Accion/cargaFormaPagoVenta.php',
            data: { formapago: formapago },
            type: 'post',
            cache: false,
            success: function (r) {
                subPagina('ventatotal.php');
            }
        });
}

//Generar Pedido
function generarVenta() {
    swal("¿Desea guardar la venta?", {
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
                            url: 'Accion/generarVenta.php',
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
                                    paginaPrincipal('ventas.php');
                                    swal("Venta generada con éxito!",
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

function cargaItemTratamiento(id,cantidad) {

    $.ajax
        ({
            url: 'Accion/cargaItemTratamiento.php',
            data: { id: id, cantidad:cantidad },
            type: 'post',
            cache: false,
            success: function (r) {
                // location.reload();
                subPagina('ventatratamientodetalle.php');
            }
        });
}

//Borrar Producto del pedido
function borrarItemTratamiento(id) {
    swal("¿Desea eliminar el tratamiento?", {
        icon: "warning",
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
                            url: 'Accion/borrarItemTratamiento.php',
                            data: { id: id },
                            type: 'post',
                            cache: false,
                            success: function (r) {

                                // location.reload();
                                subPagina('ventatratamientodetalle.php')

                            }
                        });
                    break;

            }
        });
}

function verificarCantidades() {

    $.ajax
        ({
            url: 'Accion/verificarCantidades.php',
            type: 'post',
            cache: false,
            success: function (r) {
                if (r != "") {
                    switch (r) {
                        case "No":
                            swal("Debe elegir al menos un producto o tratamiento", {
                                buttons: false,
                                icon: "error",
                                timer: 3000,
                            });
                            break;
                        case "tratamiento":
                            paginaPrincipal("ventamedico.php");
                            break;
                        default:
                            paginaPrincipal("ventaformapago.php");
                            break;
                    }
                    
                }
     
            }
        });
}

function cargaVentaMedico(id) {
    $.ajax
        ({
            url: 'Accion/cargaVentaMedico.php',
            data: { id: id },
            type: 'post',
            cache: false,
            success: function (r) {
               paginaPrincipal('ventaformapago.php');
            }
        });
}

//Listado de ventas
function cargaVentaDetalle(id) {
    param = { Id: id };
    subPagina('ventasitems.php', param);
}

function cargaVenta(id) {
    $.ajax
        ({
            url: 'Accion/cargaVenta.php',
            data: { id: id },
            type: 'post',
            cache: false,
            success: function (r) {
                paginaPrincipal('ventafecha.php');
            }
        });
}

//Borrar Producto del pedido
function eliminarVenta(id) {
    swal("¿Desea eliminar el venta?", {
        icon: "warning",
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
                            url: 'Accion/eliminarVenta.php',
                            data: { id: id },
                            type: 'post',
                            cache: false,
                            success: function (r) {
                                paginaPrincipal('ventas.php');
                                swal("Venta eliminada!",
                                    {
                                        buttons: false,
                                        icon: "success",
                                        timer: 3000,
                                    });

                            }
                        });
                    break;

            }
        });
}