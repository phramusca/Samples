# Markdown rendering on client side

Example can be seen live on a free.fr webiste here: <http://phramusca.free.fr/samples/internet/client-side/javascript/markdown/>

- `index.html`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Markdown Renderer</title>
    <script src="https://cdn.jsdelivr.net/npm/showdown/dist/showdown.min.js"></script>
</head>
<body>
    <div id="content"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('showdown-readme.md.md') // Adjust path to your Markdown file
                .then(response => response.text())
                .then(markdown => {
                    const converter = new showdown.Converter();
                    const html = converter.makeHtml(markdown);
                    document.getElementById('content').innerHTML = html;
                })
                .catch(error => console.error('Error loading Markdown:', error));
        });
    </script>
</body>
</html>
```

- [showdown-readme.md](showdown-readme.md)
