Room13TwigExtensions
====================

A collection of useful twig helpers and extensions extracted from my projects for reusability. In the following sections each filter/function is described by its own section.

Configuration
--------------------
By default, nothing has to be configured. However it is possible to remove/customize the prefix of all filters/functions by setting the prefix configuration value.

So if no naming conflicts exist, you can remove the prefix by the following configuration:

    room13_twig_extensions:
        prefix: ~

Filter: room13_entity_url
--------------------

Have you every beeing bugged to pass all route variables manually?

    {{url('my_route_name',{id:entity.id,version:entity.version})

When you want to change the routes variables and use the slug instead of the id, you will have to edit every single occurance of the route generation. By using the room13_entity_url helper you will just have to change the route definition and all url's will change on the fly.
This filter just needs an entity and a route name and will extract the properties based on the routes variable names.

    {{entity|room13_entity_url('my_route_name')}}

Contributions
--------------------
So far, only me.

Anything to add? Please fork and request a pull.