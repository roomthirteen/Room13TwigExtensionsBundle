Room13TwigExtensions
====================

A collection of useful twig helpers and extensions extracted from my projects for reusability. In the following sections each filter/function is described by its own section.

room13_entity_url
--------------------

Have you every beeing bugged to pass all route variables manually?

    {{url('my_route_name',{id:entity.id,version:entity.version})

This filter just needs an entity and a route name and will extract the properties based on the routes variable names.

    {{entity|room13_entity_url('my_route_name')}}

