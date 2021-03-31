/*BASE DE DATOS SISTEMA VERDULERÍA*/
Create database consultorio;

CREATE TABLE usuarios(
id int AUTO_INCREMENT,
nombre varchar(40) not null,
apellido varchar(40) not null,
dni varchar(20), 
especialidad varchar(100),
matriculanacional varchar(100),
matriculaprovincial varchar(100),
user varchar(20) not null,
pass varchar(200) not null,
perfil varchar(20) not null,/*Admin o mozo*/
ultimoingreso datetime not null default CURRENT_TIMESTAMP,
baja varchar(10) default 'False',
primary key(id)
);

INSERT INTO usuarios(user,pass,perfil,nombre,apellido) values('admin','$2y$10$UkJVr/OvxPrJ4NjHcd4D1eFY.rCaGm/A60/8Wst9rpwI53skL7d.C','admin','Federico','Rocchetti');


CREATE table obrassociales
(
    id int AUTO_INCREMENT not null,
    nombre varchar(100) not null,
    baja varchar(10) default 'False',
    primary key (Id) );

CREATE table formaspago
(
    id int AUTO_INCREMENT not null,
    nombre varchar(100) not null,
    baja varchar(10) default 'False',
    primary key (Id) );

Create table tratamientos(
    id int AUTO_INCREMENT,
    nombre varchar(200),
    precio double(8,2),
    porcentajemedico double(8,2),
    baja varchar(10) default 'False',
    primary key(Id)
);

CREATE table productos(
    id int AUTO_INCREMENT,
    codigo varchar(10) not null,
    denominacion varchar(200) not null,
    marca varchar(50) not null,
    stock int not null,
    preciolista double(10,2) not null,
    precioefectivo double(10,2) not null,
    preciocompra double(10,2) not null,
    baja varchar(10) default 'False',

    primary key(Id));

CREATE table proveedores(
    id int AUTO_INCREMENT,
    nombre varchar(100) not null,
    contacto varchar(100) not null,
    email varchar(200) not null,
    telefono varchar(20) not null,
    direccion varchar(300) not null,
    datosbancarios varchar(200) not null,
    comentarios varchar(2000) not null,
    baja varchar(10) default 'False',

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

