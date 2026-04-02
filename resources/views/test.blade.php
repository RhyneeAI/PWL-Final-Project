<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Tailwind</title>
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
</head>
<body>
    <div class="p-6 bg-blue-500 text-white rounded-lg m-4">
        ✅ Tailwind sudah jalan!
    </div>
    
    <div class="grid grid-cols-3 gap-4 p-4">
        <div class="bg-red-500 p-4 rounded text-white">Box 1</div>
        <div class="bg-green-500 p-4 rounded text-white">Box 2</div>
        <div class="bg-yellow-500 p-4 rounded text-white">Box 3</div>
    </div>
</body>
</html>
