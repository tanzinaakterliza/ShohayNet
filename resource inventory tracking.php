
<?php
// Database Connection
include 'db_config.php';

$success_message = "";
$error_message = "";

// Handle Add New Item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    $item_name = trim($_POST['item_name']);
    $category  = $_POST['category'];
    $location  = trim($_POST['location']);
    $quantity  = (int)$_POST['quantity'];
    $status    = 'Available';

    if (!empty($item_name) && !empty($location) && $quantity > 0) {
        $sql = "INSERT INTO resources (item_name, category, location, quantity, status, description) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $description = "Added from inventory page";

        $stmt->bind_param("sssiss", $item_name, $category, $location, $quantity, $status, $description);
        
        if ($stmt->execute()) {
            $success_message = "✅ New item added successfully to database!";
        } else {
            $error_message = "❌ Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "❌ Please fill all fields correctly.";
    }
}

// Fetch all resources for display
$sql = "SELECT * FROM resources ORDER BY last_updated DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources Inventory - ShohayNet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap');
        body { background-color: #f0f7ff; font-family: 'Fredoka', sans-serif; margin: 0; padding: 0; }
        .sidebar { transition: 0.4s ease; transform: translateX(100%); }
        .sidebar.active { transform: translateX(0); }
        .status-badge { font-size: 10px; font-weight: bold; padding: 2px 8px; border-radius: 4px; border: 1px solid; text-transform: uppercase; cursor: pointer; transition: 0.2s; }
        .status-badge:hover { opacity: 0.8; transform: scale(1.05); }
        .cat-tag { font-size: 11px; padding: 2px 10px; border-radius: 20px; border: 1px solid; cursor: pointer; transition: 0.2s; }
        .cat-tag:hover { background-color: #fdfdfd; }
        .row-orange { background-color: #ffe8d6; }
        .row-green { background-color: #d8f3dc; }
        .row-yellow { background-color: #faedcd; }
        .row-gray { background-color: #e5e5e5; }
        .stat-card { cursor: pointer; transition: transform 0.2s; }
        .stat-card:active { transform: scale(0.95); }
   </style>
</head>
<body class="relative min-h-screen pb-16">

    <div id="sidebar" class="fixed top-0 right-0 sidebar w-80 h-full bg-white shadow-2xl z-50 p-6 overflow-y-auto">
        <div class="flex justify-between items-center mb-6 border-b pb-3">
            <h2 class="text-xl font-bold text-green-800">Our Key Features</h2>
            <button onclick="toggleMenu()" class="text-3xl">&times</button>
        </div>
        <nav class="space-y-1">
            <a href="disaster.php" class="block p-3 hover:bg-green-50 rounded">Real Time Disaster Updates</a>
            <a href="livelocation.php" class="block p-3 hover:bg-green-50 rounded">Live Location Mapping</a>
            <a href="resource inventory tracking.php" class="block p-3 bg-green-700 text-white rounded shadow-md">Resource Inventory Tracking</a>
            <a href="volunteer_signup.php" class="block p-3 hover:bg-green-50 rounded">Volunteer Sign-up & Assignment</a>
            
            <a href="emergency report.php" class="block p-3 hover:bg-green-50 rounded">Emergency Reporting Tool</a>
            <a href="chat communication.php" class="block p-3 hover:bg-green-50 rounded">Chat & Communication Hub</a>
            <a href="aid-request.php" class="block p-3 hover:bg-green-50 rounded">Aid Request Form</a>
            <a href="offline mode.php" class="block p-3 hover:bg-green-50 rounded">Offline Mode Support</a>
            <a href="data_analytics.php" class="block p-3 hover:bg-green-50 rounded">Data Analytics Dashboard</a>
            <a href="integration.php" class="block p-3 hover:bg-green-50 rounded">Integration with Government Services</a>
        </nav>
    </div>

    <header class="max-w-7xl mx-auto flex justify-between items-center p-6">
        
             
            
            <span class="text-2xl font-black text-green-800">ShohayNet</span>
        </div>
        <div class="flex gap-6">
            <a href="index.php" class="text-2xl text-green-800"><i class="fas fa-home"></i></a>
            <button onclick="toggleMenu()" class="text-2xl text-green-800"><i class="fas fa-bars"></i></button>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 mb-10">
        <h1 class="text-3xl font-black text-green-700 mb-8">Resources Inventory</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div onclick="showStatInfo('Total Items', '350', 'Total items registered in the ShohayNet database across all hubs.')" class="stat-card bg-[#e9edc9] p-5 rounded-3xl flex items-center gap-4 shadow-sm">
                <div class="bg-white p-3 rounded-xl"><i class="far fa-calendar-alt text-2xl"></i></div>
                <div><p class="text-xs font-bold">Total Items</p><p class="text-2xl font-black">350</p></div>
            </div>
            <div onclick="showStatInfo('Available Items', '215', 'Stock that is ready to be dispatched immediately for relief.')" class="stat-card bg-[#e9edc9] p-5 rounded-3xl flex items-center gap-4 shadow-sm">
                <div class="bg-white p-3 rounded-xl"><i class="far fa-check-square text-2xl"></i></div>
                <div><p class="text-xs font-bold">Available Items</p><p class="text-2xl font-black">215</p></div>
            </div>
            <div onclick="showStatInfo('Checked Out', '135', 'Items currently in use by volunteers or disaster management teams.')" class="stat-card bg-[#e9edc9] p-5 rounded-3xl flex items-center gap-4 shadow-sm">
                <div class="bg-white p-3 rounded-xl"><i class="far fa-star text-2xl"></i></div>
                <div><p class="text-xs font-bold">checked out</p><p class="text-2xl font-black">135</p></div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 mb-6 items-center">
            <select onchange="filterByCategory(this.value)" class="border p-2 rounded-md bg-white text-sm">
                <option value="all">Category: All</option>
                <option value="Medical">Medical Supplies</option>
                <option value="Food">Food & Water</option>
                <option value="Shelter">Shelter</option>
            </select>
            <select onchange="filterByLocation(this.value)" class="border p-2 rounded-md bg-white text-sm">
                <option value="all">Location : All</option>
                <option value="Dhaka">Dhaka Center</option>
                <option value="Rajshahi">Rajshahi Office</option>
                <option value="Chittagong">Chittagong</option>
                <option value="Khulna">Khulna Branch</option>
                <option value="Sylhet">Sylhet Camp</option>
            </select>
            <select onchange="filterByStatus(this.value)" class="border p-2 rounded-md bg-white text-sm">
                <option value="all">status: All</option>
                <option value="Available">Available</option>
                <option value="Low Stock">Low Stock</option>
                <option value="Checked Out">Checked Out</option>
                <option value="In Maintenance">In Maintenance</option>
            </select>
            <div class="relative flex-grow max-w-xs">
                <input type="text" id="searchInput" onkeyup="searchItems()" placeholder="search items..." class="w-full border p-2 rounded-md text-sm">
                <i class="fas fa-search absolute right-3 top-3 text-gray-400 text-xs"></i>
            </div>
            <button onclick="addNewItem()" class="bg-[#f37021] text-white px-6 py-2 rounded-md font-bold shadow-md hover:bg-orange-600 transition">Add New Item</button>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left text-sm" id="inventoryTable">
                <thead class="bg-gray-50 border-b text-gray-600 font-bold">
                    <tr>
                        <th class="p-4">Item Name</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Location</th>
                        <th class="p-4">Quantity</th>
                        <th class="p-4">status</th>
                        <th class="p-4 cursor-pointer hover:text-green-700" onclick="sortByDate()">Last updated <i class="fas fa-chevron-down ml-1"></i></th>
                        <th class="p-4">Actions</th>
                    </tr>
                </thead>
            <?php
// Database Connection
include 'db_config.php';   // তোমার db_config.php ফাইলের নাম যদি অন্য হয় তাহলে সেটা দাও

// Fetch all resources
$sql = "SELECT * FROM resources ORDER BY last_updated DESC";
$result = mysqli_query($conn, $sql);
?>

                <tbody id="tableBody">            
                    <tr class="section-header bg-gray-200 font-bold border-b"><td colspan="7" class="px-4 py-1 italic"> 🏥Medical & Health Supplies</td></tr>
                    <tr class="item-row row-orange border-b" data-category="Medical" data-location="Dhaka" data-status="Low Stock">
                        <td class="p-4 font-bold flex items-center gap-2"><i class="fas fa-first-aid text-red-600"></i> Medical Kits</td>
                        <td class="p-4"><span onclick="showDetails('Medical Kits', 'Complete first aid kit with bandages, antiseptics, and tools.')" class="cat-tag bg-orange-200 border-orange-300">Medical Kits <span class="text-red-600 ml-1">18</span></span></td>
                        <td class="p-4">Dhaka Center</td><td class="p-4 font-bold">18</td>
                        <td class="p-4"><span onclick="changeStatus(this)" class="status-badge bg-orange-100 text-orange-700 border-orange-400">Low Stock</span></td>
                        <td class="p-4 text-gray-500">2 days ago</td>
                        <td class="p-4 flex gap-1">
                            <button onclick="processItem('Check-Out', 'Medical Kits')" class="bg-green-700 text-white px-3 py-1 rounded text-xs">Check-Out <i class="fas fa-chevron-down"></i></button>
                            <button onclick="advancedOptions('Medical Kits')" class="bg-white border px-3 py-1 rounded">...</button>
                        </td>
                    </tr>
                    <tr class="item-row row-green border-b" data-category="Medical" data-location="Rajshahi" data-status="Available">
                        <td class="p-4 font-bold flex items-center gap-2">💊 Emergency Medicines</td>
                        <td class="p-4"><span onclick="showDetails('Emergency Medicines', 'Antibiotics, Paracetamol, and Saline packets.')" class="cat-tag bg-green-200 border-green-300">Emergency Medicines</span></td>
                        <td class="p-4">Rajshahi Office</td><td class="p-4 font-bold">20</td>
                        <td class="p-4"><span onclick="changeStatus(this)" class="status-badge bg-green-500 text-white border-green-600">Available</span></td>
                        <td class="p-4 text-gray-500">Today</td>
                        <td class="p-4 flex gap-1">
                            <button onclick="processItem('Return', 'Emergency Medicines')" class="bg-orange-300 text-orange-900 px-3 py-1 rounded text-xs font-bold">Return</button>
                            <button onclick="editItem('Emergency Medicines')" class="bg-white border px-3 py-1 rounded text-xs font-bold">Edit</button>
                            <button onclick="showMoreInfo('Emergency Medicines')" class="bg-white border px-3 py-1 rounded text-xs">More <i class="fas fa-chevron-down"></i></button>
                        </td>
                    </tr>
                    <tr class="item-row row-yellow border-b" data-category="Medical" data-location="Chittagong" data-status="Available">
                        <td class="p-4 font-bold flex items-center gap-2">🛄 ORS Packets</td>
                        <td class="p-4"><span onclick="showDetails('ORS Packets', 'Oral Rehydration Solution for dehydration.')" class="cat-tag bg-orange-200 border-orange-300">ORS Packets</span></td>
                        <td class="p-4">Chittagong</td><td class="p-4 font-bold">60</td>
                        <td class="p-4"><span onclick="changeStatus(this)" class="status-badge bg-green-500 text-white border-green-600">Available</span></td>
                        <td class="p-4 text-gray-500">1 week ago</td>
                        <td class="p-4 flex gap-1">
                            <button onclick="processItem('Check-Out', 'Medical Kits')" class="bg-green-700 text-white px-3 py-1 rounded text-xs">Check-Out <i class="fas fa-chevron-down"></i></button>
                            <button onclick="advancedOptions('Medical Kits')" class="bg-white border px-3 py-1 rounded">...</button>
                        </td>
                    </tr>

                    <tr class="item-row row-green border-b" data-category="Medical" data-location="Khulna Branch" data-status="Available">
                        <td class="p-4 font-bold flex items-center gap-2">🧤Mask & Goggles</td>
                        <td class="p-4"><span onclick="showDetails('Mask & Goggles', 'Disposable face masks and safety goggles.')" class="cat-tag bg-green-200 border-green-300">Mask & Goggles</span></td>
                        <td class="p-4">Khulna Branch</td><td class="p-4 font-bold">150</td>
                        <td class="p-4"><span onclick="changeStatus(this)" class="status-badge bg-green-500 text-white border-green-600">Available</span></td>
                        <td class="p-4 text-gray-500">4 days ago</td>
                        <td class="p-4 flex gap-1">
                            <button onclick="processItem('Check-Out', 'Mask & Goggles')" class="bg-green-700 text-white px-3 py-1 rounded text-xs">Check-Out <i class="fas fa-chevron-down"></i></button>
                            <button onclick="advancedOptions('Mask & Goggles')" class="bg-white border px-3 py-1 rounded">...</button>
                        </td>
                    </tr>


                    <tr class="section-header bg-gray-200 font-bold border-b"><td colspan="7" class="px-4 py-1 italic">🍱Food & Water Supplies</td></tr>
                    <tr class="  item-row row-green border-b" data-category="Food" data-location="Chittagong" data-status="Checked Out">

                     <td class="p-4 font-bold flex items-center gap-2">🍶Bottled Drinking Water</td>
                        <td class="p-4"><span onclick="showDetails('Bottled Drinking Water', '500ml mineral water bottles.')" class="cat-tag bg-green-200 border-green-300">Bottled Drinking Water</span></td>
                        <td class="p-4">Chittagong</td><td class="p-4 font-bold">85</td>
                        <td class="p-4"><span onclick="changeStatus(this)" class="status-badge bg-orange-200 text-orange-800 border-orange-300">Checked Out</span></td>
                        <td class="p-4 text-gray-500">2 days ago</td>
                        <td class="p-4 flex gap-1">
                            <button onclick="processItem('Return', 'Bottled Drinking Water')" class="bg-orange-300 text-orange-900 px-3 py-1 rounded text-xs font-bold">Return</button>
                            <button onclick="editItem('Drinking Water')" class="bg-white border px-3 py-1 rounded text-xs font-bold">Edit</button>
                            <button onclick="advancedOptions('Drinking Water')" class=" bg-white border px-3 py-1 rounded">...</button>
                        </td>
                    </tr>
                    <tr class="item-row row-orange border-b  " data-category="Food" data-location="Sylhet Camp" data-status="Available">
                        <td class="p-4 font-bold flex items-center gap-2">🥫Dry food Packets</td>
                        <td class="p-4"><span onclick="showDetails('Dry Food', 'Dry food packets for emergency use.')" class="cat-tag bg-green-200 border-green-300">Dry Food packets</span></td>
                        <td class="p-4">Sylhet Camp</td><td class="p-4 font-bold">70</td>
                        <td class="p-4"><span onclick="changeStatus(this)" class="status-badge bg-green-500 text-white border-green-600">Available</span></td>
                        <td class="p-4 text-gray-500">4 days ago</td>
                        <td class="p-4 flex gap-1">
                            <button onclick="processItem('Out of Stock', 'Dry Food')" class="bg-white border px-3 py-1 rounded text-xs font-bold">Out of Stock </button>
                            <button onclick="advancedOptions('Dry Food')" class="bg-white border px-3 py-1 rounded">...</button>
                        </td>
                        </td>
                    </tr>
 <tr class="section-header bg-gray-200 font-bold border-b"><td colspan="7" class="px-4 py-1 italic">🏠Shelter and Relief Equipment</td></tr>
                    <tr class="item-row row-orange border-b" data-category="Shelter" data-location="Rajshahi office " data-status="In Maintenance"> 
                        <td class="p-4 font-bold flex items-center gap-2">🏠Tents</td>
                        <td class="p-4"><span onclick="showDetails('Tents', 'Portable shelter tents for emergency use.')" class="cat-tag bg-green-200 border-green-300">Tents</span></td>
                        <td class="p-4">Rajshahi office </td><td class="p-4 font-bold">14</td>
                        <td class="p-4"><span onclick="changeStatus(this)" class="status-badge bg-orange-200 text-orange-800 border-orange-300">In Maintenance</span></td>
                        <td class="p-4 text-gray-500">2 days ago</td>
                        <td class="p-4 flex gap-1">
                            <button onclick="processItem('Check-Out', 'Tents')" class="bg-green-700 text-white px-3 py-1 rounded text-xs">Check-Out <i class="fas fa-chevron-down"></i></button>
                            <button onclick="advancedOptions('Tents')" class="bg-white border px-3 py-1 rounded">...</button>
                        </td>
                    </tr>

                    <tr class="item-row row-green border-b" data-category="Shelter" data-location="Rajshahi office " data-status="In Maintenance"> 
                        <td class="p-4 font-bold flex items-center gap-2">🛏️Blankets</td>
                        <td class="p-4"><span onclick="showDetails('Blankets', 'Warm blankets for emergency use.')" class="cat-tag bg-green-200 border-green-300">Blankets</span></td>
                        <td class="p-4">Khulna Branch </td><td class="p-4 font-bold">50</td>
                        <td class="p-4"><span onclick="changeStatus(this)" class="status-badge bg-green-500 text-white border-green-600">Available</span></td>
                        <td class="p-4 text-gray-500">5 days ago</td>
                        <td class="p-4 flex gap-1">
                            
                       <button onclick="showMoreInfo('Blankets')" class="bg-blue-500 text-white px-3 py-1 rounded text-xs">More <i class="fas fa-chevron-down"></i></button>
                            <button onclick="advancedOptions('Blankets')" class="bg-white border px-3 py-1 rounded">...</button>
                       <button onclick="advancedOptions('Blankets')" class="bg-white border px-3 py-1 rounded">...</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

       
        </div>
    </main>

    <footer class="bg-green-700 text-white text-center py-4 fixed bottom-0 w-full z-20">
        <p class="text-xs">©2026 ShohayNet , All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleMenu() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // --- NEW FUNCTIONS ---
        function showStatInfo(title, count, info) {
            Swal.fire({
                title: title,
                html: `<div class='p-4'><h1 class='text-4xl font-bold text-green-700'>${count}</h1><p class='mt-2 text-gray-600'>${info}</p></div>`,
                confirmButtonColor: '#15803d',
                icon: 'info'
            });
        }

        function filterByLocation(loc) {
            let rows = document.querySelectorAll('.item-row');
            rows.forEach(row => {
                row.style.display = (loc === 'all' || row.dataset.location.includes(loc)) ? '' : 'none';
            });
        }

        function filterByStatus(stat) {
            let rows = document.querySelectorAll('.item-row');
            rows.forEach(row => {
                row.style.display = (stat === 'all' || row.dataset.status === stat) ? '' : 'none';
            });
        }

        function showMoreInfo(item) {
            Swal.fire({
                title: `Resource Timeline: ${item}`,
                html: `<div class='text-left text-sm'><p class='mb-1'>📍 <b>Last Location:</b> Verified in Warehouse</p><p class='mb-1'>📦 <b>Condition:</b> Excellent</p><p>👤 <b>Manager:</b> ShohayNet Admin Team</p></div>`,
                confirmButtonText: 'View Full Log',
                confirmButtonColor: '#15803d'
            });
        }

        function advancedOptions(item) {
            Swal.fire({
                title: 'Advanced Options',
                text: `What would you like to do with ${item}?`,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Download QR',
                denyButtonText: `Transfer Stock`,
                confirmButtonColor: '#15803d'
            }).then((result) => {
                if (result.isConfirmed) { Swal.fire('QR Generated', '', 'success'); }
                else if (result.isDenied) { Swal.fire('Transfer Request Sent', '', 'info'); }
            });
        }

        function sortByDate() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Sorting items by latest updates...',
                showConfirmButton: false,
                timer: 1500
            });
        }

        function searchItems() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let rows = document.querySelectorAll('.item-row');
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(input) ? '' : 'none';
            });
            document.querySelectorAll('.section-header').forEach(h => h.style.display = input ? 'none' : '');
        }

        function showDetails(name, desc) {
            Swal.fire({
                title: `<strong>${name}</strong>`,
                icon: 'info',
                html: `<div class='text-left'><p><b>Product Details:</b> ${desc}</p><br><p><b>Inventory ID:</b> SN-${Math.floor(Math.random()*1000)}</p></div>`,
                confirmButtonColor: '#15803d'
            });
        }

        function changeStatus(el) {
            Swal.fire({
                title: 'Update Status',
                input: 'select',
                inputOptions: {
                    'Available': 'Available',
                    'Low Stock': 'Low Stock',
                    'Checked Out': 'Checked Out',
                    'In Maintenance': 'In Maintenance'
                },
                inputPlaceholder: 'Select status',
                showCancelButton: true,
                confirmButtonColor: '#15803d'
            }).then((result) => {
                if (result.value) {
                    el.innerText = result.value;
                    Swal.fire('Updated!', 'Status has been changed.', 'success');
                }
            });
        }

        function processItem(type, item) {
            Swal.fire({
                title: `${type} Confirmation`,
                text: `Are you sure you want to ${type.toLowerCase()} ${item}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#15803d'
            }).then((res) => {
                if (res.isConfirmed) {
                    Swal.fire('Success!', `${item} processed for ${type}.`, 'success');
                }
            });
        }

        function editItem(name) {
            Swal.fire({
                title: `Edit ${name}`,
                input: 'number',
                inputLabel: 'Update Stock Quantity',
                showCancelButton: true,
                confirmButtonColor: '#15803d'
            }).then((result) => {
                if (result.value) {
                    Swal.fire('Saved!', 'Stock updated successfully.', 'success');
                }
            });
        }

        function filterByCategory(cat) {
            let rows = document.querySelectorAll('.item-row');
            let headers = document.querySelectorAll('.section-header');
            rows.forEach(row => {
                row.style.display = (cat === 'all' || row.dataset.category === cat) ? '' : 'none';
            });
            headers.forEach(h => h.style.display = (cat === 'all') ? '' : 'none');
        }

        function action(task) {
            Swal.fire({
                title: 'Processing...',
                html: `System is performing <b>${task}</b> operation.`,
                timer: 1200,
                timerProgressBar: true,
                didOpen: () => { Swal.showLoading(); }
            }).then(() => {
                Swal.fire({ icon: 'success', title: 'Done!', text: `${task} completed.`, confirmButtonColor: '#15803d' });
            });
        }

       
       
       
       function addNewItem() {
    Swal.fire({
        title: 'Add New Inventory Item',
        html: `
            <input id="swal-input1" class="swal2-input" placeholder="Item Name *">
            <select id="swal-input2" class="swal2-input">
                <option value="Medical">Medical Supplies</option>
                <option value="Food">Food & Water</option>
                <option value="Shelter">Shelter</option>
            </select>
            <input id="swal-input3" class="swal2-input" placeholder="Location (e.g. Dhaka Center) *">
            <input id="swal-input4" type="number" class="swal2-input" placeholder="Quantity *" min="1">
        `,
        focusConfirm: false,
        confirmButtonText: 'Save to Database',
        confirmButtonColor: '#f37021',
        showCancelButton: true,
        preConfirm: () => {
            const name = document.getElementById('swal-input1').value.trim();
            const cat  = document.getElementById('swal-input2').value;
            const loc  = document.getElementById('swal-input3').value.trim();
            const qty  = parseInt(document.getElementById('swal-input4').value);

            if (!name || !loc || isNaN(qty) || qty <= 0) {
                Swal.showValidationMessage('Please fill all required fields correctly');
                return false;
            }
            return { name, cat, loc, qty };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { name, cat, loc, qty } = result.value;

            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `add_item=1&item_name=${encodeURIComponent(name)}&category=${cat}&location=${encodeURIComponent(loc)}&quantity=${qty}`
            })
            .then(response => response.text())
            .then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Item has been added to the database.',
                    timer: 1800
                }).then(() => {
                    location.reload();   // পেজ রিফ্রেশ করে নতুন ডেটা দেখাবে
                });
            })
            .catch(() => {
                Swal.fire('Error', 'Failed to save item. Try again.', 'error');
            });
        }
    });
}
        
    </script>
</body>
</html>