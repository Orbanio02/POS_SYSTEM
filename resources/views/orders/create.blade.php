@extends('layouts.app')
@section('content')
    <h1>Create Order (POS)</h1>

    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div id="items">
            <div class="item-row">
                <select name="items[0][product_id]">
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }}
                            ({{ $p->stock }})
                        </option>
                    @endforeach
                </select>
                <input type="number" name="items[0][quantity]" value="1" min="1">
            </div>
        </div>

        <button type="button" onclick="addRow()">Add Item</button>
        <br><br>
        <button type="submit">Create Order</button>
    </form>

    <script>
        let idx = 1;

        function addRow() {
            const items = document.getElementById('items');
            const div = document.createElement('div');
            div.className = 'item-row';
            div.innerHTML = ` <select name="items[${idx}][product_id]">
        @foreach ($products as $p)
        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->stock }})</option>
        @endforeach
    </select>
    <input type="number" name="items[${idx}][quantity]" value="1" min="1">`;
            items.appendChild(div);
            idx++;
        }
    </script>
@endsection
