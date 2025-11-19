// server.js — minimal Express server to serve your static HTML/JS/CSS project
const express = require('express');
const path = require('path');
const app = express();
const PORT = process.env.PORT || 3000;

// Serve static files from the project root
app.use(express.static(path.join(__dirname, '/')));

// Serve index.html on root
app.get('/', (req, res) => res.sendFile(path.join(__dirname, 'index.html')));

// Optional: fallback for 404 pages
app.use((req, res) => res.status(404).sendFile(path.join(__dirname, '404.html')));

// Start server
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));
