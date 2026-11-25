<html>
<head>
    <title>{$title|default:"Default Page Title"}</title>
</head>
<body>
<h1>{$title|default:"Default Page Title"}</h1>
<p>{$message|default:"Default content goes here."}</p>
{$posts|default:[]|@json_encode}
</body>
</html>
