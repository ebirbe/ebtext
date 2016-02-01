# EBText

EBText es una capa para el desarrollo de aplicaciones SMS que se sostiene sobre el servidor de mensajería provisto por SmsTools3. Está desarrollada en lenguaje PHP debido a que es un lenguaje altamente conocido y manejado por gran cantidad de desarrolladores.

# Instalación

La siguiente información se hace tomando como ejemplo una instalación basada en el sistema operativo Debian 8, para otros sistemas operativos quizás tenga que adaptar los pasos a realizar según.

## Dependencias

EBText como se ha mencionado anteriormente hace uso del servidor de mensajería provisto por **SmsTools3**, por lo tanto esa será nuestra pincipal dependencia. También, debido a que EBText funciona como Script de PHP que no requiere del navegador para ser llamado, debemos instalar la herramienta **php5-cli**.

Podemos ejecutar el siguiente comando para instalar ambas dependencias:

    aptitude install smstools php5-cli

Adicionalmente, como vamos a trabajar con modems USB, es bueno asegurarse que el sistema cuenta con los paquetes de manejo de este tipo de dispositivos.

	aptitude install usb-modeswitch modemmanager

## Instalar ebtext

Para descargar EBText podemos ejecutar el siguiente comando:

    wget https://github.com/erickcion/ebtext/archive/ebtext_v1.0.zip

Podemos instalar EBText en una ruta del sistema, este paso es opcional, pero nos puede evitar usar innecesaiamente largas. Se puede utilizar por ejemplo la carpeta `/opt`, e instalarlo de la siguiente manera:

	# Crear la carpeta donde instalaremos EBText
	mkdir /opt/ebtext
	# Copiar los archivos
	cp -rvf ./* /opt/ebtext/
	# Enlazar los ejecutables en /usr/bin
	ln -s /opt/ebtext/ebtext /usr/bin/
	ln -s /opt/ebtext/ebtbulk /usr/bin/

Podemos comprobar que el sistema se ha instalado correctamente en nuestro sistema ejecutando el comando `ebtext` sin nungún parametro, con lo cual obtendremos la siguiente respuesta:

    Nothing to do here... I quit!

Si obtienes esa respuesta, ebtext se encuentra instalado correctamente en tu sistema.

## Configurar dispositivo y opciones del servidor SMS

Según la documentación de SmsTools3, debes configurar los parametros necesarios para que tus dispositivos sean manejados por el servidor de mensajes.

	vim.tiny /etc/smsd.conf

### Configurar opciones Generales

	loglevel = 5
	incoming_utf8 = yes
	decode_unicode_text = yes
	date_filename = 1

### Configurar manejadores de evento

	eventhandler = /opt/ebtext/ebtext

### Configurar ruta del dispositivo GSM

	device = /dev/ttyUSB0
	# Activar mensajes entrantes
	incoming = yes
	# Alta prioridad a mensajes entrantes
	incoming = high

#### Para Modems Huawei Activar lectura desde SIM

	[GSM1]
	init = ATE0+CMEE=1;+CREG=2;+CPMS="MT","MT","MT"
	# Inicia la busqueda desde mensajes desde el indice 0
	check_memory_method = 2
	# Otros
	baudrate = 115200

## Activar lista negra de numeros no deseados

	mkdir /etc/smstools
	cp -vf examples/blacklist /etc/smstools/

## Levantar el servicio

	sudo service smstools restart
