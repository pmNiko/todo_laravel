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

---

<br/>


| 🚀 Layout & Index View                       |
|----------------------------------------------|
| [Blade Laravel](https://laravel.com/docs/11.x/blade)|

<br/>

- Representa una plantilla base que define la estructura básica reutilizable de una o más páginas.
- Para esta prueba de concepto vamos a utilizar Bootstrap como plantilla de estilos.
- Creamos una estructura básica para el Layout:
    - base.blade.php  (Diseño base)
    - yield (slot para insertar un parcel html)

```php
    <div class="container">
        @yield('content')  <<<<Este es un slot con el identificador **content**>>>>
    </div>
```

```php
    @extends('layouts.base')  <<<<Hereda la plantilla base>>>>

    @section('content')  <<<<Accede al slot **content** y le inyecta el contenido a continuación>>>>
    <div>
        <p>Contenido inyectado al slot del Layout</p>
    </div>
    @endsection
```

Bien ahora debemos liberar la ruta que permite acceso al controller.
```php

    // Importación de rutas de controlador
    use App\Http\Controllers\TaskController;

    Route::get('/', function () {
        return view('welcome');
    });

    // Enlazamos la ruta al controlador del recurso
    Route::resource('tasks', TaskController::class);
```

Por último retornamos la vista **index** en la acción index del controller.
```php
    public function index()
    {
        return view('index');
    }
```

🚀 Ya deberiamos ser capaces de ver la viste index de tareas
- [url con Laragon](https://todo.test/tasks)
- [url con Laragon y ssl](http://todo.test/tasks)
- [url con **php artisan serve**](http://localhost:8000/tasks)

---

<br/>


| 🦀 Create Task                              |
|----------------------------------------------|
| [@csrf](https://laravel.com/docs/11.x/csrf)  |

<br/>

- View -> Create
```php
    @extends('layouts.base')  

    @section('content') 
    <div>
        // Formulario de creación de una tarea
        .....
        // Tanto los actions como los href se mueven entre secciones con el método **route**    
        <form action="{{route('tasks.store')}}" method="POST">
            @csrf   //Adherimos este decorador
            <div class="row">
        ....
    </div>
    @endsection
```

- Action controller -> create (retorna el formulario)
```php
    public function create()
    {
        return view('create');
    }
```

- Action controller -> store (lógica de almacenamiento del registro)
```php
    public function store(Request $request)
    {
        // Muestra todo el objeto request
        dd($request->all());
    }
```

De esta manera si enviamos el formulario veriamos por pantalla los datos ingresados.

```json
    array:5 [▼ // app\Http\Controllers\TaskController.php:39
        "_token" => "X6zDQPFI66v7yo9S95FqJmaLC5wMZgMD2EqihWgk"
        "title" => "Primer tarea"
        "description" => "Esta es mi primer tarea creada"
        "due_date" => "1994-03-12"
        "status" => "Pendiente"
    ]
```
