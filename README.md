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

