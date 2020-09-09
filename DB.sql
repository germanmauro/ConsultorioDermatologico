/*BASE DE DATOS SISTEMA VERDULERÍA*/
Create database verduleria;

CREATE TABLE zona(
Id int AUTO_INCREMENT not null,
Nombre varchar(20),
Ubicacion varchar(20),
CantidadPedidos int default 1,
Lunes varchar(2) default 'No',
Martes varchar(2) default 'No',
Miercoles varchar(2) default 'No',
Jueves varchar(2) default 'No',
Viernes varchar(2) default 'No',
Sabado varchar(2) default 'No',
Domingo varchar(2) default 'No',

Baja varchar(10) default 'False',

primary key(Id)
);

CREATE TABLE usuario(
Nombre varchar(40) not null,
Apellido varchar(40) not null,
Telefono varchar(20) not null,
Direccion varchar(200) not null,
DNI varchar(20), /*DNI CUIT*/
User varchar(100) not null,
Email varchar(100),
Pass varchar(200) not null,
Perfil varchar(20) not null,/*Admin o mozo*/
UltimoIngreso datetime not null default CURRENT_TIMESTAMP,
Zona int,
Baja varchar(10) default 'False',
primary key(User),
foreign key(Zona) References zona(Id)
);
/*Perfiles
admin
cliente
*/
INSERT INTO usuario(User,Pass,Perfil,Nombre,Apellido,Telefono) values('admin','$2y$10$UkJVr/OvxPrJ4NjHcd4D1eFY.rCaGm/A60/8Wst9rpwI53skL7d.C','admin','Diego','Administrador','00');


CREATE table categoria
(
    Id int AUTO_INCREMENT not null,
    Nombre varchar(100) not null,
    Prioridad SMALLINT(6) default 0,
    Baja varchar(10) default 'False',
    primary key (Id) );

CREATE table producto(
    Id int AUTO_INCREMENT,
    Codigo varchar(10) not null,
    Nombre varchar(50) not null,
    Precio double(10,2) not null,
    Stock double(10,2) not null,
    Tipo varchar(20) not null,
    Categoria int not null,
    Imagen varchar(3000) default '',
    Habilitado varchar(2) default 'Si',
    Baja varchar(10) default 'False',

    foreign key (Categoria) References categoria(Id),
    primary key(Id));


Create table pedido(
    Id int AUTO_INCREMENT,
    Fecha Date not null,
    Direccion varchar(200),
    Telefono varchar(20),
    FechaEntrega Date not null,
    Zona int not null,
    Estado varchar(20) default 'Generado',
    FormaPago varchar(20) default 'Efectivo',
    Comentarios varchar(120) default '',
    Cliente varchar(100) not null,
    Total double(8,2),

    foreign key(Cliente) REFERENCES usuario(User),
    primary key(Id)
);
/*Estados
1- Generado,
2- Cerrado,
3- Cobrado,
4- Cancelado
*/

Create table pedidodetalle(
    Id int AUTO_INCREMENT,
    Producto int not null,
    Nombre varchar(100) not null,
    Pedido int not null,
    Cantidad double(10,2) not null,
    Precio double(8,2) not null,
    Total double(8,2),

    primary key(Id),
    foreign key(Pedido) REFERENCES pedido(Id),
    foreign key(Producto) REFERENCES producto(Id)
);
Create table configuracion(
    MontoEnvio double(8,2) not null
    Leyenda varchar(100) not null

);
insert into configuracion values('0','Los valores son aproximados');

