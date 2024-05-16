<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPA with Laravel and Vanilla JS</title>
    <style>
        .data-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .data-item img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div id="app">
        <form id="dataForm">
            <input type="text" id="name" placeholder="Name" required>
            <input type="text" id="address" placeholder="Address" required>
            <input type="text" id="gender" placeholder="Gender" required>
            <input type="file" id="image" required>
            <button type="submit">Add</button>
        </form>
        <button id="sortName">Sort by Name</button>
        <button id="sortId">Sort by ID</button>
        <div id="dataList"></div>
    </div>

    <script>
        let data = [];

        document.getElementById('dataForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const name = document.getElementById('name').value;
            const address = document.getElementById('address').value;
            const gender = document.getElementById('gender').value;
            const image = document.getElementById('image').files[0];

            const newData = {
                id: Date.now(),
                name,
                address,
                gender,
                image
            };

            data.push(newData);
            renderData();
        });

        document.getElementById('sortName').addEventListener('click', function() {
            data.sort((a, b) => a.name.localeCompare(b.name));
            renderData();
        });

        document.getElementById('sortId').addEventListener('click', function() {
            data.sort((a, b) => a.id - b.id);
            renderData();
        });

        function renderData() {
            const dataList = document.getElementById('dataList');
            dataList.innerHTML = '';

            data.forEach(item => {
                const div = document.createElement('div');
                div.className = 'data-item';

                const img = document.createElement('img');
                img.src = URL.createObjectURL(item.image);

                const info = document.createElement('div');
                info.innerHTML = `<p>${item.name}</p><p>${item.address}</p><p>${item.gender}</p>`;

                const removeBtn = document.createElement('button');
                removeBtn.innerText = 'Remove';
                removeBtn.addEventListener('click', function() {
                    data = data.filter(d => d.id !== item.id);
                    renderData();
                });

                div.appendChild(img);
                div.appendChild(info);
                div.appendChild(removeBtn);

                dataList.appendChild(div);
            });
        }
    </script>
</body>
</html>
