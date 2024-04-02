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

<br/>


| 🔐 Store Task                              |
|----------------------------------------------|
| [fillable vs guarded](https://www.linkedin.com/pulse/understanding-laravels-fillable-vs-guarded-shashika-nuwan/)  |

<br/>

- Estos son dos middlewares que apuntan a habilitar u omitir el acceso de actualización masiva de datos.

```php
    class Task extends Model
    {
        use HasFactory;

        // propiedades admitidas para asignación masiva
        protected $fillable = ['title', 'description', 'due_date', 'state'];
    }
```

- Entonces ya podriamos crear nuestra primer tarea y verla reflejada en base de datos.

--- 

<br/>


| ✅ Validate Properties Task                  |
|----------------------------------------------|
| [Request Validation](https://laravel-news.com/validation)  |

<br/>

- Si las validaciones no se cumplen arrojaran errores los culaes mostraremos en el Layout base.
- Con la directiva ->with() guarda un mensaje en una variable de sesion del tipo flash. 
```php
    public function store(Request $request): RedirectResponse // Tipo de respuesta
    {
        $request->validate([
            'title'       => 'required',
            'description' => 'required',
        ]);
        Task::create($request->all());
        return redirect()->route('tasks.index')->with('success','Tarea creada con éxito!');
    }
```
- base.blade.php
```php
    .....
    <body class="bg-dark text-white">
        <div class="container">
            // Mensajes de éxito
            @if (Session::get('success'))
                <div class="alert alert-success">
                    <strong>{{Session::get('success')}}</strong><br><br>                    
                </div>
            @endif

            // Mensajes de error
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Ha ocurrido un error!</strong><br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        ....
```

--- 

<br/>


| 📑 All Tasks                  |
|----------------------------------------------|
| [Request Validation](https://laravel-news.com/validation)  |

<br/>

- Controller method index
```php
    public function index()
    {
        $tasks = Task::orderBy("created_at","desc");
        return view('index', ['tasks'=> $tasks]);
    }
```

En el index de tareas recorremos con un foreach las tareas y las mostramos en una tabla.

--- 

<br/>


| 🔪 Paginator                                |
|----------------------------------------------|
| [Fuente](https://codeanddeploy.com/blog/laravel/laravel-8-pagination-example-using-bootstrap-5)  |

<br/>

- AppServiceProvider
```php
    public function boot()
    {
        Paginator::useBootstrap();
    }
```

- TaskController.php
```php
    public function index()
    {
        $tasks = Task::orderBy("created_at","asc")->paginate(1);
        return view('index', ['tasks'=> $tasks]);
    }
```

- index.blade.php
```php
    // luego de la tabla de tareas
    <div class="d-flex">
        {!! $tasks->links() !!}
    </div>
```

- Con solo estos pasos logramos un paginador de tareas.

--- 

<br/>


| 🆙 Update Tasks                                |
|----------------------------------------------|
|  |

<br/>

- Comenzamos añadiendo el enlace a la acción edit

- index.blade.php
```php
    <a href="{{route('tasks.edit', $task)}}" class="btn btn-warning">Editar</a>
```

- Generamos la vista **edit** 
- TaskController.php
```php
    public function edit(Task $task): View
    {
        return view('edit', ['task' => $task]);
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->all());
        return redirect()->route('tasks.index')->with('success', 'Tarea actualizada con éxito!');
    }
```