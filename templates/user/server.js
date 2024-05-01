const express = require('express');
const multer = require('multer');
const fs = require('fs');

const app = express();
const port = 3000;

// Configure Multer to handle file uploads
const upload = multer({ dest: 'uploads/' });

// Define route for image upload
app.post('/upload-image', upload.single('image'), (req, res) => {
    // Get the uploaded image file
    const imageFile = req.file;

    // Perform any necessary processing here, such as saving the image or further manipulation
    // Example: Save the image to a specific directory
    fs.renameSync(imageFile.path, `uploads/${imageFile.originalname}`);

    // Send a response back to the client
    res.json({
        success: true,
        message: 'Image uploaded successfully',
        imageUrl: `http://127.0.0.1:8000/uploads/${imageFile.originalname}` // Provide the URL to access the uploaded image
    });
});

// Start the server
app.listen(port, () => {
    console.log(`Server is listening on port ${port}`);
});
