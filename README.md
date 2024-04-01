### Todo Laravel

_____


| 📝  Recursos Markdown                    |
|----------------------------------------------|
| [Template](https://gist.github.com/cseeman/8f3bfaec084c5c4259626ddd9e516c61) |
| [Extensiones VScode](https://github.com/mjbvz/vscode-github-markdown-preview?tab=readme-ov-file) |
| [Wiki VScode](https://marketplace.visualstudio.com/items?itemName=lostintangent.wikilens) |


<br/>

----

| 📁 Database                                  |
|----------------------------------------------|

Una vez creada la base de datos con el nombre **<mi_base_de_datos>** 
debemos enlazarla a la variable de entorno de nuestro proyecto.
En el archivo .env 

```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=mi_base_de_datos  <<<En eeste punto>>>
```

Para comprobar que se ha conectado correctamente corremos el commando:

```bash
    $ php artisan migrate
```

Con esto se generarán los registro por defecto que necesita laravel para funcionar, se puede comprobar en el gestor de BD.

---

<br/>



| ⛰️ Migration                                |
|----------------------------------------------|
| [Data types](https://www.heinsoe.com/blog/85) |

Creamos una migración para generar un cambio incremental en la BD.
Estará directamente relacionado con el Modelo Task.

```bash
    $ php artisan make:migration create_tasks_table
```
Ahora solo restaria editar la migración según conveniencia para que cuente con los campos que nos interesan.

```php
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->dateTime('due_date')->nullable();
        $table->enum('state', ['PENDING', 'IN_PROGRESS', 'COMPLETE'])->default('PENDING');
        $table->timestamps();
    });
```
Corremos la migración y podriamos ver reflejada la tabla en la BD.

---

<br/>


| 🖌️ Controller & Model                       |
|----------------------------------------------|
| [Fuente extra](https://ashutosh.dev/laravel-10-all-about-controllers/) |

```bash
    $ php artisan make:controller TaskController --resource --model=Task
```

Esto genera el Model Task y el controller con las acciones CRUD.
Model tambien va en Mayuscula y singular