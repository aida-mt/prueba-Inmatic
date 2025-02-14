### Documentación de decisiones

1. Uso de Transacciones en la Creación de Asientos Contables
	•	Decisión: Utilizar transacciones en la base de datos para la creación de asientos contables.
	•	Motivo: Las transacciones garantizan que todas las operaciones relacionadas con la creación de asientos contables y movimientos sean seguras. Si alguna parte del proceso falla, todas las acciones se deshacen, evitando inconsistencias en los datos.

2. Validación de Movimientos Contables Balanceados
	•	Decisión: Validar que los movimientos contables estén equilibrados antes de ser almacenados en la base de datos.
	•	Motivo: Un asiento contable debe siempre cumplir con la regla básica de que los débitos sean igual a los créditos. Validar esta condición ayuda a mantener la integridad de los registros contables y evitar errores de contabilización.

3. Definición de Tipos de Factura (Compra y Venta)
	•	Decisión: Se ha decidido que el tipo de factura se determinará basándose en la presencia o ausencia de un proveedor.
	•	Motivo: Para simplificar el sistema de tipos de facturas, se utiliza la existencia de un proveedor para determinar si la factura es de tipo “compra” o “venta”. Esta decisión permite una rápida clasificación de las facturas sin requerir un campo extra o lógica compleja.

5. No Eliminar Facturas, Solo Cancelarlas
	•	Decisión: Las facturas no se eliminan, sino que se marcan como “canceladas” en caso de que sea necesario.
	•	Motivo: Eliminar registros contables podría comprometer la trazabilidad de las operaciones. Al cambiar el estado de la factura a “cancelada”, se conserva el historial completo y se mantiene la integridad de los datos contables.

6. Manejo de Errores con Excepciones Personalizadas
	•	Decisión: Utilizar excepciones personalizadas para manejar errores durante el proceso de creación de asientos contables y sus movimientos.
	•	Motivo: Las excepciones personalizadas permiten identificar y manejar los errores específicos de la lógica de negocios.

7. No Se Implementó Autenticación y Autorización
    •	Decisión: Se ha decidido no implementar autenticación ni autorización en esta fase del proyecto, ni validar las peticiones API utilizando JWT, Passport, Sanctum o OAuth2.
	•	Motivo: La prueba técnica se enfoca en la gestión de facturas y asientos contables, sin involucrar la gestión de usuarios. Por lo tanto, se priorizó la implementación de la funcionalidad principal de la aplicación, dejando de lado la autenticación y autorización para esta fase.

8. Uso de un campo enum en lugar de una tabla relacionada
	•	Decisión: Se ha decidido utilizar un campo enum en la tabla invoices y accounting_entries para gestionar los estados de las facturas y asientos contables, en lugar de crear una tabla relacionada.
	•	Motivo: La elección de usar un enum se basa en la simplicidad y rapidez del desarrollo. En este caso, los estados de las facturas son valores limitados y fijos, por lo que un campo enum ofrece una solución directa y eficiente. Para asegurar la escalabilidad de la aplicación a largo plazo, se considera que una tabla relacionada sería la opción más adecuada.

9. Uso de Servicios para la Creación de Asientos y Movimientos en Lugar de Controladores
    • Decisión: Se ha decidido utilizar un servicio para la creación de asientos contables y movimientos.
    • Motivo: La elección de usar un Service Class se basa en la separación de responsabilidades y la organización del código.

10. Uso de un Observer para la Gestión de Eventos de Facturas
    • Decisión: Se ha decidido utilizar un Observer para la validación contable de facturas.
    • Motivo: La decisión se basa en la necesidad de validar las reglas contables necesarias antes de crear un asiento contable. El Observer se encarga de gestionar estas validaciones tras la creación de la factura, asegurando que todo esté correcto si la factura ha sido registrada sin problemas.
