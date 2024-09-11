const express = require('express');
const app = express();
const path = require('path');

// Servir archivos estáticos
app.use(express.static('public'));

// Ruta para manejar URLs sin .html
app.get('/:page', (req, res) => {
  const page = req.params.page;
  res.sendFile(path.join(__dirname, 'public', `${page}.html`));
});

app.listen(3000, () => {
  console.log('Servidor corriendo en puerto 3000');
});
