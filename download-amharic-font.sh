#!/bin/bash
echo "To support Amharic text in PDFs, download an Amharic font (e.g., Abyssinica SIL or Nyala)"
echo "and place the .ttf file in the storage/fonts/ directory."
echo ""
echo "Download Abyssinica SIL from: https://software.sil.org/abyssinica/download/"
echo ""
echo "After downloading, run:"
echo "  php artisan vendor:publish --provider=\"Barryvdh\DomPDF\ServiceProvider\""
echo "  # Then update config/dompdf.php with the font path"
