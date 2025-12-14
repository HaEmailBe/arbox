<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Elevator Exercise</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #EEEEEE;
            min-height: 100vh;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 10vh;
            padding: 1rem;
        }

        .header h1 {
            font-size: 2rem;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .config-panel {
            background: white;
            border-radius: 1.5rem;
            padding: 3rem;
            max-width: 500px;
            margin: 5rem auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .config-panel h1 {
            text-align: center;
            color: #1e293b;
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        .config-group {
            margin-bottom: 1.5rem;
        }

        .config-group label {
            display: block;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .config-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .config-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-initialize {
            width: 100%;
            padding: 1rem;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-initialize:hover {
            background: #2563eb;
        }

        .hidden {
            display: none !important;
        }

        .main-content {
            display: flex;
            gap: 2rem;
        }

        .elevator-section {
            display: flex;
            gap: 1rem;
        }

        .floor-label-item {
            height: 60px;
            font-weight: 600;
        }

        .elevator-shafts {
            display: flex;
        }

        .elevator-column {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .shaft {
            position: relative;
            width: 80px;
            background: white;
            border-left: 1px solid #94a3b8;
        }

        .shaft.last {
            border-right: 1px solid #94a3b8;
        }

        .floor-marker {
            position: absolute;
            width: 100%;
            height: 60px;
            border-top: 1px solid #94a3b8;
        }

        .floor-marker:first-of-type {
            border-bottom: 1px solid #94a3b8;
        }

        .elevator-car {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: bottom 0.1s linear;
        }

        .elevator-car svg {
            width: 100%;
            height: 100%;
        }

        .elevator-car.idle img {
            filter: brightness(0);
        }

        .elevator-car.moving img {
            filter: invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);
        }

        .elevator-car.arrived img {
            filter: invert(64%) sepia(59%) saturate(458%) hue-rotate(81deg) brightness(119%) contrast(119%);
        }

        .elevator-arrow {
            color: red;
            font-weight: bold;
            font-size: 1.5rem;
            z-index: 1;
        }

        .floor-control-item {
            height: 60px;
        }

        .call-button {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 1rem;
        }

        .call-button.call {
            background: #22c55e;
            color: white;
        }

        .call-button.call:hover {
            background: #16a34a;
        }

        .call-button.waiting {
            background: #ef4444;
            color: white;
            cursor: not-allowed;
        }

        .call-button.arrived {
            background: transparent;
            color: #22c55e;
            border: 2px solid #22c55e;
            cursor: not-allowed;
        }

        .call-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .pulse {
            animation: pulse 1s infinite;
        }
    </style>
</head>

<body>
    <div id="configPanel" class="config-panel">
        <h1>Elevator System</h1>
        <div class="config-group">
            <label for="numFloors">Number of Floors</label>
            <input type="number" id="numFloors" min="2" max="50" value="10">
        </div>
        <div class="config-group">
            <label for="numElevators">Number of Elevators</label>
            <input type="number" id="numElevators" min="1" max="10" value="5">
        </div>
        <button class="btn-initialize" onclick="initializeSystem()">Initialize System</button>
    </div>

    <div class="container col-4 mx-auto hidden" id="mainContainer">
        <!-- Main System -->
        <div id="mainContent">
            <div class="header col-12">
                <h1 class="text-center">Elevator Exercise</h1>
                <button class="btn btn-warning ms-3" onclick="returnAllToGround()">Return All to Ground</button>
            </div>
        </div>

        <div id="systemContainer" class="main-content">
            <div class="elevator-section">
                <div id="floorLabels" class="floor-labels-column d-flex flex-column"></div>
                <div id="elevatorShafts" class="elevator-shafts"></div>
            </div>
            <div class="controls-panel">
                <div id="floorControls" class="floor-controls d-flex flex-column"></div>
            </div>
        </div>
    </div>

    <audio id="arrivalSound">
        <source src="/sound/elevator_door.wav" type="audio/wav">
    </audio>

    <script>
        // System state
        let numFloors = 10;
        let numElevators = 5;
        let elevators = [];
        let floors = [];
        let queue = [];

        // Get ordinal suffix for floor numbers
        function getOrdinalSuffix(n) {
            if (n === 0) return 'Ground Floor';
            const s = ['th', 'st', 'nd', 'rd'];
            const v = n % 100;
            return `${n}${s[(v - 20) % 10] || s[v] || s[0]}`;
        }

        // Initialize the system
        function initializeSystem() {
            numFloors = parseInt(document.querySelector('#numFloors').value) || 10;
            numElevators = parseInt(document.querySelector('#numElevators').value) || 5;

            // Hide config, show main content
            document.querySelector('#configPanel').classList.add('hidden');
            document.querySelector('#mainContainer').classList.remove('hidden');
            // Initialize elevators
            elevators = Array.from({
                length: numElevators
            }, (_, i) => ({
                id: i,
                currentFloor: 0,
                targetFloor: null,
                status: 'idle', // idle, moving, arrived
                direction: null, // up, down
                element: null
            }));

            // Initialize floors
            floors = Array.from({
                length: numFloors
            }, (_, i) => ({
                floor: i,
                status: 'call', // call, waiting, arrived
                elevatorId: null,
                callTime: null,
                arrivalTime: null,
                buttonElement: null
            }));

            renderSystem();
        }

        // Render the elevator system
        function renderSystem() {
            const shaftsContainer = document.querySelector('#elevatorShafts');
            const controlsContainer = document.querySelector('#floorControls');
            const labelsContainer = document.querySelector('#floorLabels');

            shaftsContainer.innerHTML = '';
            controlsContainer.innerHTML = '';
            labelsContainer.innerHTML = '';

            const floorHeight = 60;
            const shaftHeight = numFloors * floorHeight;

            // Render floor labels on the left
            for (let i = numFloors - 1; i >= 0; i--) {
                const labelItem = document.createElement('div');
                labelItem.classList.add('floor-label-item', 'd-flex', 'align-items-center', 'justify-content-end',
                    'fw-semibold');
                labelItem.textContent = getOrdinalSuffix(i);
                labelsContainer.appendChild(labelItem);
            }

            // Render elevator shafts
            elevators.forEach((elevator, index) => {
                const column = document.createElement('div');
                column.className = 'elevator-column';

                const shaft = document.createElement('div');
                shaft.className = 'shaft';
                shaft.style.height = `${shaftHeight}px`;

                if (index === elevators.length - 1) {
                    shaft.classList.add('last');
                }

                // Add floor markers
                for (let i = 0; i < numFloors; i++) {
                    const marker = document.createElement('div');
                    marker.className = 'floor-marker';
                    marker.style.top = `${(numFloors - 1 - i) * floorHeight}px`;
                    shaft.appendChild(marker);
                }

                // Add elevator car
                const car = document.createElement('div');
                car.classList.add('elevator-car', 'idle');
                car.style.bottom = '5px';
                car.dataset.elevatorId = elevator.id;
                car.innerHTML = `
                    <span class="elevator-arrow"></span>
                    <img src="/images/icons8-elevator.svg" alt="Elevator" style="width: 80%; height: 80%;">
                `;
                elevator.element = car;
                shaft.appendChild(car);

                column.appendChild(shaft);
                shaftsContainer.appendChild(column);
            });

            // Render floor controls (buttons only)
            for (let i = numFloors - 1; i >= 0; i--) {
                const control = document.createElement('div');
                control.classList.add('floor-control-item', 'd-flex', 'align-items-center', 'justify-content-center');

                const button = document.createElement('button');
                button.classList.add('call-button', 'call');
                button.textContent = 'Call';
                button.onclick = () => callElevator(i);
                floors[i].buttonElement = button;

                control.appendChild(button);
                controlsContainer.appendChild(control);
            }
        }

        // Call an elevator
        function callElevator(floorNum) {
            const floor = floors[floorNum];
            if (floor.status !== 'call') return;

            // Update floor status to waiting
            floor.status = 'waiting';
            floor.callTime = Date.now();
            updateFloorButton(floorNum);

            // Find closest available elevator
            const availableElevators = elevators.filter(e => e.status === 'idle');

            if (availableElevators.length > 0) {
                const closest = availableElevators.reduce((prev, curr) => {
                    const prevDist = Math.abs(prev.currentFloor - floorNum);
                    const currDist = Math.abs(curr.currentFloor - floorNum);
                    return currDist < prevDist ? curr : prev;
                });

                assignElevator(closest.id, floorNum);
            } else {
                // Add to queue if all elevators are busy
                queue.push(floorNum);
            }
        }

        // Assign elevator to floor
        function assignElevator(elevatorId, targetFloor) {
            const elevator = elevators[elevatorId];
            elevator.targetFloor = targetFloor;
            elevator.status = 'moving';
            elevator.direction = targetFloor > elevator.currentFloor ? 'up' : 'down';

            floors[targetFloor].elevatorId = elevatorId;

            updateElevatorDisplay(elevatorId);
            moveElevator(elevatorId, targetFloor);
        }

        // Move elevator with smooth animation
        function moveElevator(elevatorId, targetFloor) {
            const elevator = elevators[elevatorId];
            const startFloor = elevator.currentFloor;
            const distance = Math.abs(targetFloor - startFloor);
            const duration = distance * 1000; // 1 second per floor
            const startTime = Date.now();

            function animate() {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(elapsed / duration, 1);

                elevator.currentFloor = startFloor + (targetFloor - startFloor) * progress;
                updateElevatorPosition(elevatorId);

                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    elevator.currentFloor = targetFloor;
                    elevatorArrived(elevatorId, targetFloor);
                }
            }

            animate();
        }

        // Elevator arrived at floor
        function elevatorArrived(elevatorId, targetFloor) {
            const elevator = elevators[elevatorId];
            const floor = floors[targetFloor];

            // Play sound
            const sound = document.querySelector('#arrivalSound');
            sound.currentTime = 0;
            sound.play().catch(e => console.log('Audio play failed:', e));

            // Calculate travel time
            const travelTime = floor.callTime ? Date.now() - floor.callTime : 0;
            floor.arrivalTime = travelTime;

            // Update elevator status
            elevator.status = 'arrived';
            elevator.targetFloor = null;
            updateElevatorDisplay(elevatorId);

            // Update floor status
            floor.status = 'arrived';
            updateFloorButton(targetFloor);

            // Wait 2 seconds then reset
            setTimeout(() => {
                elevator.status = 'idle';
                elevator.direction = null;
                updateElevatorDisplay(elevatorId);

                floor.status = 'call';
                floor.elevatorId = null;
                floor.callTime = null;
                floor.arrivalTime = null;
                updateFloorButton(targetFloor);

                // Process queue
                if (queue.length > 0) {
                    const nextFloor = queue.shift();
                    assignElevator(elevatorId, nextFloor);
                }
            }, 2000);
        }

        // Update elevator position
        function updateElevatorPosition(elevatorId) {
            const elevator = elevators[elevatorId];
            const floorHeight = 60;
            const bottom = elevator.currentFloor * floorHeight + 5;
            elevator.element.style.bottom = `${bottom}px`;
        }

        // Update elevator display (color and icon)
        function updateElevatorDisplay(elevatorId) {
            const elevator = elevators[elevatorId];
            const car = elevator.element;
            const icon = car.querySelector('.elevator-arrow');

            car.className = `elevator-car ${elevator.status}`;

            if (elevator.status === 'moving') {
                icon.textContent = elevator.direction === 'up' ? '↑' : '↓';
            } else if (elevator.status === 'arrived') {
                icon.textContent = '';
                car.classList.add('pulse');
            } else {
                icon.textContent = '';
                car.classList.remove('pulse');
            }
        }

        // Update floor button
        function updateFloorButton(floorNum) {
            const floor = floors[floorNum];
            const button = floor.buttonElement;

            button.className = `call-button ${floor.status}`;

            if (floor.status === 'call') {
                button.textContent = 'Call';
                button.disabled = false;
            } else if (floor.status === 'waiting') {
                button.textContent = 'Waiting...';
                button.disabled = true;
            } else if (floor.status === 'arrived') {
                const time = (floor.arrivalTime / 1000).toFixed(1);
                button.textContent = floorNum !== 0 ? `Arrived (${time}s)` : 'Arrived';
                button.disabled = true;
            }
        }

        function returnAllToGround() {
            elevators.forEach((elevator) => {
                if (elevator.status === 'idle' && elevator.currentFloor !== 0) {
                    assignElevator(elevator.id, 0);
                }
            });
        }
    </script>
</body>

</html>
