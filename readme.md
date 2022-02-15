## eJornal

Sistema de salud. La empresa Jornal Salud cuenta con empleados (medicos y enfermeros) que trabajan en las locaciones
de sus clientes (empresas como por ejemplo: Carrefour, Coto). En estas empresas donde trabajan los empleados de Jornal
Salud hay empleados de la propia empresa, que el sistema llama Nomina (osea trabajadores de una nomina).
En este punto podemos decir que:
- Admin: eJornal
- Empleado: Medicos y enfermeros que trabajan para eJornal
- Clientes: Son clientes de eJornal. Empresas donde los medicos y enfermeros trabajan.

Los empleados son los que mas funcionalidades tienen, ya que, al fichar su entrada, pueden realizar multiplicidad de actividades.
Es importante destacar que los Clientes tambien se pueden loggear y hasta tienen una API a disposición si el admin la habilita.


## Tecnologias

- Laravel 6.2
- PHP 7.2
- Javascript/Jquery
- composer 2

## Laravel Auditing

El sistema cuenta con:
- laravel-auditing 12

Esto se utiliza en algunas tablas.


## Composer
Es importante tener instalado composer 2 para valerse de la siguiente dependencia:
- "doctrine/dbal": "2.*",

Con ésta podemos modificar/alterar las migraciones ya creadas en local o en produccion.


## Entornos
El sitio se hostea el Hostinger. En caso de necesitar ingresar solicitar datos a Javier.

A la fecha hay 2 entornos:

- Test:
sitio => http://e2-test.jornalsalud.com/

IP SSH =>  185.201.11.44
Puerto SSH => 65002
SSH Username => u693804549
SSH Pass => Vicente2020!

FTP => IP 185.201.11.60
FTP => user u693804549
FTP => Pass Vicente2020!

mysql
user => u693804549_wuCA5
pass => Vicente2020!
nombre base => u6938 04549_iZ2NG

Acceso por phpmyadmin => https://auth-db154.hostinger.com/index.php?db=u693804549_iZ2NG

Dejo acceso formateado rápido para SSH => ssh -p 65002 u693804549@185.201.11.60


- Prod:
sitio => e2.jornalsalud.com

ftp => e2.jornalsalud.com
user => u646345361
pass => Vicente2020!

SSH port => 65002
user => u646345361
pass => Vicente2020!

Dejo acceso formateado rápido para SSH => ssh -p 65002 u646345361@185.201.11.44


## FRONTEND
Se utilizó el framework MDBoostrap.
Link directo:
https://mdbootstrap.com/docs/b4/jquery/

Allí tendrá varios componentes visuales para utilizar.
También hay estilos propios que encontrará logicamente en /resources/sass



## IMPORTANTE

### Tablas
El sistema fue pensado de una forma y fue cambiando durante la marcha por pedidos que fueron surgiendo. Es importante aclarar que algunas cosas que se decidieron cambiar ya no eran posibles en los tiempos requeridos porque no fue pensado así originalmente o porque estaba online ya funcionando de determinada manera, por tanto algunos registros se consultan por ID en forma harcodeada.
Hay algunas tablas que se consultan por datos concretos porque no fue pensado.
Por ejemplo los Tipos de Ausentismo:
- ID 8 y ID 9: Son casos sospechosos o confirmados de covid. Se utilizan para las querys y logica en varias partes del sitio. Están harcodeados (8, 9). Hoy dia se pueden crear, modificar y eliminar tipos de ausentismos. El cliente sabe que no debe eliminarlos, pero es un riesgo que exista la posibilidad. Por el momento no se tomó otra decisión al respecto.
- ID 12 Es de accidente. Esto se utiliza para las estadisticas y logica tambien.

### Tabla Users
- Id cliente actual:
Es para cuando un empleado está loggeado saber en que empresa está trabajando, osea en que cliente de eJornal.

- Id cliente relacionar:
Es para saber, este usuario, a que cliente pertenece. Esto solo se completa cuando el usuario tiene relacion con un ID Cliente. Si es un user empleado o admin, entonces en esta columna verás null.

- ID especialidad:
Esto puede ser "medico" o "enfermero." Son rol de usuario empleado que pueden tener alguno de estos tipos.

- Drive:
Aquí se coloca el link al drive de un user de tipo empleado porque alli la empresa sube los comprobantes de pago.

- Permiso desplegables:
Esto es para limitar al user empleado en la posibilidad de usar algunos select. La limitacion se hace desde los blade con if

- Personal interno:
Es para saber si un usuario empleado es parte de eJornal o está terciarizado.

- Archivo - hasharchivo / matricula - hashmatricula / Etc:
Es para el user empleado. Un requerimiento que ingresó despues para poder subir contenido.
