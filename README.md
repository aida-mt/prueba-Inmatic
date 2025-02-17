### Documentación de decisiones

1. <b>Uso de transacciones en la creación de asientos contables</b></br>
	•	Decisión: Utilizar transacciones en la base de datos para la creación de asientos contables.</br>
	•	Motivo: Las transacciones garantizan que todas las operaciones relacionadas con la creación de asientos contables y movimientos sean seguras. Si alguna parte del proceso falla, todas las acciones se deshacen, evitando inconsistencias en los datos.</br></br>

2. <b>Validación de cuadre de movimientos contables</b></br>
	•	Decisión: Validar que los movimientos contables estén equilibrados antes de ser almacenados en la base de datos.</br>
	•	Motivo: Un asiento contable debe siempre cumplir con la regla básica de que los débitos sean igual a los créditos. Validar esta condición ayuda a mantener la integridad de los registros contables y evitar errores contables.</br></br>

3. <b>Definición de tipos de factura</b></br>
	•	Decisión: Se ha decidido que el tipo de factura se determinará basándose en la presencia o ausencia de un proveedor.</br>
	•	Motivo: Para simplificar el sistema de tipos de facturas, se utiliza la existencia de un proveedor para determinar si la factura es de tipo “compra” o “venta”. Esta decisión permite una rápida clasificación de las facturas sin requerir una lógica compleja.</br></br>

5. <b>No eliminar facturas, sólo cancelarlas</b></br>
	•	Decisión: Las facturas no se eliminan, sino que se marcan como “canceladas” en caso de que sea necesario.</br>
	•	Motivo: Eliminar registros contables podría comprometer la trazabilidad de las operaciones. Al cambiar el estado de la factura a "cancelled", se conserva el historial completo y se mantiene la integridad de los datos contables.</br></br>

6. <b>Manejo de errores con excepciones personalizadas</b></br>
	•	Decisión: Utilizar excepciones personalizadas para manejar errores durante el proceso de creación de asientos contables y sus movimientos. Se añadirán códigos de error HTTP (como 404, 422, etc.) para mejorar la claridad en las respuestas de la API.</br>
	•	Motivo: Las excepciones personalizadas permiten identificar y manejar los errores específicos de la lógica de negocios.</br></br>

7. <b>Se ha omitido la autenticación y autorización</b></br>
    •	Decisión: Se ha decidido no implementar autenticación ni autorización en esta fase del proyecto, ni validar las peticiones API utilizando JWT, Passport, Sanctum o OAuth2.</br>
	•	Motivo: La prueba técnica se enfoca en la gestión de facturas y asientos contables, sin involucrar la gestión de usuarios. Por lo tanto, se priorizó la implementación de la funcionalidad principal de la aplicación, dejando de lado la autenticación y autorización.</br></br>

8. <b>Uso de un campo enum en lugar de una tabla relacionada</b></br>
	•	Decisión: Se ha decidido utilizar un campo enum en la tabla invoices y accounting_entries para gestionar los estados de las facturas y asientos contables, en lugar de crear una tabla relacionada.</br>
	•	Motivo: La elección de usar un enum se basa en la simplicidad y rapidez del desarrollo. En este caso, los estados de las facturas son valores limitados y fijos, por lo que un campo enum ofrece una solución directa y eficiente. Para asegurar la escalabilidad de la aplicación a largo plazo, se considera que una tabla relacionada sería la opción más adecuada.</br></br>

9. <b>Uso de servicios para la creación de asientos y movimientos en lugar de controladores</b></br>
	•	Decisión: Se ha decidido utilizar un servicio para la creación de asientos contables y movimientos.</br>
	•	Motivo: La elección de usar un Service Class se basa en la separación de responsabilidades y la organización del código.</br></br>

10. <b>Uso de un observer para la gestión de eventos de facturas</b></br>
	•	Decisión: Se ha decidido utilizar un Observer para la validación contable de facturas.</br>
	•	Motivo: La decisión se basa en la necesidad de validar las reglas contables necesarias antes de crear un asiento contable. El Observer se encarga de gestionar estas validaciones tras la creación de la factura, asegurando que todo esté correcto si la factura ha sido registrada sin problemas.</br></br>

11.	<b>Tratamiento de la aplicación como si fuera para un única empresa</b></br>
	•	Decisión: La prueba técnica se desarrollará bajo el supuesto de que la aplicación está destinada a una única empresa.</br>
	•	Motivo: Dado que no se requiere la gestión de múltiples clientes o empresas para la prueba, de esta forma se simplifica el modelo de datos y la lógica de negocio.</br></br>

13. <b>Uso de un campo date para las facturas en lugar de timestamps</b></br>
	•	Decisión: Se ha decidido utilizar un campo date para almacenar la fecha de las facturas, en lugar de utilizar los campos timestamps.</br>
	•	Motivo: El uso de un campo date simplifica la lógica al enfocarse exclusivamente en la fecha de la factura. Los campos timestamps están disponibles en caso de que sea necesario realizar un registro detallado de cuándo se han guardado o modificado las facturas.</br></br>

12.	<b>Uso de scopes para el filtrado de facturas por estado y fechas</b></br>
	•	Decisión: Se ha implementado el filtrado por estado y fechas directamente en los modelos utilizando scopes, en lugar de crear una clase separada para el filtrado y usar un trait, que ofrecería una solución más escalable.</br>
	•	Motivo: Esta decisión permite un desarrollo más ágil para la prueba técnica, aunque implica una mayor carga en el modelo.</br></br>

13.	<b>Uso de MissingInvoiceNumbers como regla de validación</b></br>
	•	Decisión: Se ha decidido utilizar MissingInvoiceNumbers en InvoiceManyRequest para aplicar la validación de números de factura faltantes.</br>
	•	Motivo: Al tratar la validación de números faltantes como una regla de validación en InvoiceManyRequest, se garantiza una validación más clara y coherente cuando se envía un lote de facturas.</br></br>

14.	<b>Uso de ValidInvoiceNumber como regla de validación</b></br>
	•	Decisión: Se ha decidido utilizar ValidInvoiceNumber como regla de validación para el formato de los números de factura.</br>
	•	Motivo: Al centralizar esta lógica de validación en una regla dedicada, se facilita su mantenimiento. Además, permite integrar la validación directamente con el ecosistema de validación de Laravel, lo que asegura una mayor consistencia y facilidad de uso.</br></br>
