<html>
    <head>
        <title>To Do List</title>
    </head>
    <body>
        <h1>To Do List</h1>
        {% if tasks is not empty %}
        <p>Here are all of your tasks:</p>
        <ul>
            {% for task in tasks %}
                <li>{{ task.getDescription }}</li>
            {% endfor %}
        </ul>
        {% endif %}

        <form action='/tasks' method='post'>
            <label for='description'>Task Description</label>
            <input id='description' name='description' type='text'>

            <button type='submit'>Add task</button>
        </form>
        <form action='/deleteTasks' method='post'>
            <button type='submit'>delete</button>
        </form>
    </body>
</html>
