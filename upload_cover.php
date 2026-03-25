    function generatePdfThumbnail($pdfPath, $outputImagePath) {
        if (!extension_loaded('imagick')) {
            throw new Exception("Imagick extension is not installed");
        }

        $imagick = new Imagick();
        $imagick->setResolution(150, 150); // Better quality thumbnail
        $imagick->readImage($pdfPath . '[0]'); // Read only the first page
        $imagick->setImageFormat('jpeg');
        $imagick->writeImage($outputImagePath);
        $imagick->clear();
        $imagick->destroy();
    }
