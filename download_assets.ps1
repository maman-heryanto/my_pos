$baseUrlAdminLte = "https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist"
$baseUrlBootstrap = "https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist"
$baseUrlJquery = "https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist"

$dest = "d:\app\public\vendor"

# Create directories
New-Item -ItemType Directory -Force -Path "$dest\adminlte\css"
New-Item -ItemType Directory -Force -Path "$dest\adminlte\js"
New-Item -ItemType Directory -Force -Path "$dest\bootstrap\css"
New-Item -ItemType Directory -Force -Path "$dest\bootstrap\js"
New-Item -ItemType Directory -Force -Path "$dest\jquery"

# Download AdminLTE
Invoke-WebRequest -Uri "$baseUrlAdminLte/css/adminlte.min.css" -OutFile "$dest\adminlte\css\adminlte.min.css"
Invoke-WebRequest -Uri "$baseUrlAdminLte/js/adminlte.min.js" -OutFile "$dest\adminlte\js\adminlte.min.js"

# Download Bootstrap
Invoke-WebRequest -Uri "$baseUrlBootstrap/css/bootstrap.min.css" -OutFile "$dest\bootstrap\css\bootstrap.min.css"
Invoke-WebRequest -Uri "$baseUrlBootstrap/js/bootstrap.bundle.min.js" -OutFile "$dest\bootstrap\js\bootstrap.bundle.min.js"

# Download jQuery
Invoke-WebRequest -Uri "$baseUrlJquery/jquery.min.js" -OutFile "$dest\jquery\jquery.min.js"

Write-Host "Assets downloaded successfully."
