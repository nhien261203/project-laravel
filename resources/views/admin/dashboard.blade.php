@extends('layout.admin')

@section('content')

<!-- Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded shadow text-center">
        <h4 class="text-sm text-gray-600 mb-2">👤 Users</h4>
        <p class="text-2xl font-bold">1,245</p>
    </div>
    <div class="bg-white p-6 rounded shadow text-center">
        <h4 class="text-sm text-gray-600 mb-2">🛒 Orders</h4>
        <p class="text-2xl font-bold">320</p>
    </div>
    <div class="bg-white p-6 rounded shadow text-center">
        <h4 class="text-sm text-gray-600 mb-2">💰 Revenue</h4>
        <p class="text-2xl font-bold">$12,340</p>
    </div>
</div>

<!-- Chart 1: Bar -->
<div class="bg-white p-6 rounded shadow mb-6">
    <h3 class="text-lg font-bold mb-4">📊 Monthly Sales</h3>
    <div class="flex justify-center items-center h-[400px]">
        <canvas id="salesChart" class="w-full max-w-3xl h-full"></canvas>
    </div>
</div>

<!-- Chart 2: Line -->
<div class="bg-white p-6 rounded shadow mb-6">
    <h3 class="text-lg font-bold mb-4">📈 Revenue Growth</h3>
    <div class="flex justify-center items-center h-[400px]">
        <canvas id="lineChart" class="w-full max-w-3xl h-full"></canvas>
    </div>
</div>

<!-- Chart 3: Pie -->
<div class="bg-white p-6 rounded shadow mb-6">
    <h3 class="text-lg font-bold mb-4">🥧 Product Share</h3>
    <div class="flex justify-center items-center h-[400px]">
        <canvas id="pieChart" class="w-full max-w-md h-full"></canvas>
    </div>
</div>

<!-- Chart 4: Horizontal Bar -->
<div class="bg-white p-6 rounded shadow mb-6">
    <h3 class="text-lg font-bold mb-4">📥 Page Views</h3>
    <div class="flex justify-center items-center h-[400px]">
        <canvas id="horizontalBarChart" class="w-full max-w-3xl h-full"></canvas>
    </div>
</div>

@endsection
