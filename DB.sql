/*BASE DE DATOS SISTEMA CONSULTORIO DERMA*/
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
    porcentajeproducto double(10,2) not null,
    porcentajetratamiento double(10,2) not null,
    baja varchar(10) default 'False',
    primary key (Id) );

Create table tratamientos(
    id int AUTO_INCREMENT,
    codigo varchar(10),
    denominacion varchar(200),
    precioventa double(8,2),
    preciocompra double(8,2),
    baja varchar(10) default 'False',
    primary key(Id)
);

CREATE table productos(
    id int AUTO_INCREMENT,
    codigo varchar(10) not null,
    denominacion varchar(200) not null,
    marca varchar(50) not null,
    stock int not null,
    precioventa double(10,2) not null,
    preciocompra double(10,2) not null,
    baja varchar(10) default 'False',

    primary key(Id));

CREATE table proveedores(
    id int AUTO_INCREMENT,
    empresa varchar(100) not null,
    email varchar(200) not null,
    telefono varchar(20) not null,
    direccion varchar(300) not null,
    contacto1 varchar(100) not null,
    email1 varchar(200) not null,
    telefono1 varchar(20) not null,
    contacto2 varchar(100) not null,
    email2 varchar(200) not null,
    telefono2 varchar(20) not null,
    datosbancarios varchar(2000) not null,
    comentarios varchar(2000) not null,
    baja varchar(10) default 'False',

    primary key(Id));

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



CREATE table pacientes(
    id int AUTO_INCREMENT,
    codigo varchar(10) not null,
    nombre varchar(20) not null,
    dni varchar(15) not null,
    apellido varchar(20) not null,
    obrasocial_id int not null,
    carnet varchar(30) not null,
    contacto1 varchar(100) not null,
    email varchar(200) not null,
    telefono varchar(20) not null,
    direccion varchar(200) not null,
    localidad varchar(100) not null,
    fechanacimiento date not null,
    profesion varchar(100),
    referido varchar(200),
    alta date,
    baja varchar(10) default 'False',

    foreign key(obrasocial_id) REFERENCES obrassociales(id), 
    primary key(id));

CREATE table turnos(
    id int AUTO_INCREMENT,
    fecha datetime not null,
    medico_id int not null,
    duracion tinyint not null,
    paciente_id int not null,
    tratamiento_id int not null,
    observaciones varchar(300),

    foreign key(medico_id) REFERENCES usuarios(id), 
    foreign key(paciente_id) REFERENCES pacientes(id), 
    foreign key(tratamiento_id) REFERENCES tratamientos(id), 
    primary key(id));

CREATE table dias(
    id int AUTO_INCREMENT,
    medico_id int not null,
    dia int not null,
    horadesde varchar(5) not null,
    horahasta  varchar(5) not null,
    foreign key(medico_id) REFERENCES usuarios(id), 
    primary key(id));

CREATE table bloqueos(
    id int AUTO_INCREMENT,
    medico_id int not null,
    dia date not null,
    foreign key(medico_id) REFERENCES usuarios(id), 
    primary key(id));

CREATE table bloqueosparciales(
    id int AUTO_INCREMENT,
    medico_id int not null,
    horadesde varchar(5) not null,
    horahasta  varchar(5) not null,
    motivo  varchar(100),
    dia date not null,
    foreign key(medico_id) REFERENCES usuarios(id), 
    primary key(id));

CREATE table historias(
    paciente_id int not null,
    neurologico text,
    cardiovascular text,
    endocrinologico text,
    pulmonar text,
    digestivo text,
    renal text,
    dermatologico text,
    hematologicas text,
    antecedentesotros text,
    antigangrenantes varchar(300),
    anticoagulantes varchar(300),
    analgesicos varchar(300),
    suplementosvitaminicos varchar(300),
    antidepresivos varchar(300),
    medicamentosotros text,
    alergiafarmaco varchar(300),
    alergiaanestesicolocal varchar(300),
    tabaco varchar(100),
    alcohol varchar(100),
    actividadfisica varchar(100),
    exposicionsolar varchar(100),
    toxinabotulinica varchar(100),
    acidohialuronico varchar(100),
    antecedentesquirurgicos varchar(500),
    antecedentestraumaticos text,
    cicatrizacion text,
    reaccionesvagales text,
    dismorfofobia text,
    vacunacionantitetanica text,
    fragilidadcapilar text,
    tratamientoodontologico text,
    foreign key(paciente_id) REFERENCES pacientes(id)
);

CREATE table consultas(
    id int AUTO_INCREMENT,
    paciente_id int not null,
    fecha date not null,
    motivo text,
    detalle text,
    foreign key(paciente_id) REFERENCES pacientes(id), 
    primary key(id));

CREATE table compras(
    id int AUTO_INCREMENT,
    producto_id int not null,
    fecha date not null,
    cantidad int not null,
    foreign key(producto_id) REFERENCES productos(id), 
    primary key(id));

    

    CREATE table ventas(
    id int AUTO_INCREMENT,
    paciente_id int not null,
    fecha date not null,
    formapago_id int not null,
    porcentajetratamiento double(8,2) not null,
    porcentajeproducto double(8,2) not null,
    total double(8,2),
    factura varchar(15),
    observaciones varchar(1000),
    foreign key(paciente_id) REFERENCES pacientes(id), 
    foreign key(formapago_id) REFERENCES formaspago(id), 
    primary key(id));

    CREATE table ventasproductos(
    id int AUTO_INCREMENT,
    venta_id int not null,
    producto_id int not null,
    cantidad int not null,
    preciounitario double(8,2),
    total double(8,2),
    foreign key(producto_id) REFERENCES productos(id), 
    foreign key(venta_id) REFERENCES ventas(id)
    ON DELETE CASCADE, 
    primary key(id));

    CREATE table ventastratamientos(
    id int AUTO_INCREMENT,
    venta_id int not null,
    tratamiento_id int not null,
    cantidad int not null,
    medico_id int not null,
    preciounitario double(8,2),
    total double(8,2),
    porcentaje double(8,2),
    comision double(8,2),
    foreign key(tratamiento_id) REFERENCES tratamientos(id), 
    foreign key(medico_id) REFERENCES usuarios(id), 
    foreign key(venta_id) REFERENCES ventas(id)
    ON DELETE CASCADE, 
    primary key(id));

/*Hasta acá en producción*/
CREATE table archivos(
    id int AUTO_INCREMENT,
    paciente_id int not null,
    fecha date not null,
    descripcion text,
    archivo text,
    foreign key(paciente_id) REFERENCES pacientes(id), 
    primary key(id));