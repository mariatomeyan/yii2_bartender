<?php

/** @var yii\web\View $this */

$this->title = 'Bar Tender';


?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Bartender</h1>
    </div>

    <div class="body-content">
        <div id="react-app"></div>
    </div>
</div>

<script type="text/babel">
    const { useState } = React;
    const PATH = 'http://localhost:8080/';

    function useWebSocket(url) {
        const [message, setMessage] = React.useState(null);
        React.useEffect(() => {
            const socket = new WebSocket(url);

            socket.onopen = function(event) {
                console.log('Connection open');
            };
            socket.onmessage = function(event) {
                console.log('Received: ' + event.data);
            };
            socket.onerror = function(error) {
                console.log('WebSocket Error: ', error);
            };
            socket.onclose = function(event) {
                console.log('Connection closed');
            };

            socket.addEventListener('message', (event) => {
                console.log('event', event)
                setMessage(event.data);
            });

            return () => {
                socket.close();
            };
        }, [url]);

        return message;
    }

    function OrderDrinks({onOrderPlaced}) {
        const [selectedDrink, setSelectedDrink] = useState(null);
        const [selectedQuantity, setSelectedQuantity] = useState(null);
        const messageFromServer = useWebSocket('ws://localhost:8081');


        function resetStates() {
            setSelectedDrink(null);
            setSelectedQuantity(null);
        }
        function handleDrinkSelect(drink, quantity) {
            resetStates()
            setSelectedDrink(drink);
            setSelectedQuantity(quantity);
        }

        function handleQuantitySelect(value) {
            setSelectedQuantity(value);
        }

        function getDrinksByType () {
            fetch('http://localhost:8080/drinks/' + selectedDrink, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
                .then((response) => response.json())
                .then((data) =>  {
                    console.log('data is', data)
                } )
                .catch((error) => {
                    // Handle any errors
                    console.error(error);
                });
        }
        function placeOrder() {
            fetch('http://localhost:8080/bartender/create-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    drink: selectedDrink,
                    quantity: selectedQuantity,
                })
            })
                .then((response) => {
                    response.json();
                    onOrderPlaced();
                })
                .catch((error) => {
                    // Handle any errors
                    console.error(error);
                });
        }
        const maxQuantity = selectedDrink === 'beer' ? 2 : 1;
        return (
            <div className="text-3xl font-bold text-center ">
                <div>

                    {messageFromServer && <p>Message from server: {messageFromServer}</p>}
                </div>

                What would you like to order?
                <div className="flex flex-row">
                    <Drink
                        orderDrink={() => handleDrinkSelect('beer')}
                        selected={selectedDrink === 'beer'}
                        size="145px"
                        image={'assets/images/beer.png'}
                        name="Beer"
                    />
                    <Drink
                        orderDrink={() => handleDrinkSelect('drink')}
                        selected={selectedDrink === 'drink'}
                        image={'assets/images/drink.png'}
                        name="Drink"
                    />
                </div>

                {selectedDrink && (
                    <div className="flex  flex-row">
                        <ChooseQuantity onSelect={handleQuantitySelect} max={maxQuantity}/>
                    </div>
                )}

                {
                    selectedDrink && selectedQuantity && (
                        <div className="flex  flex-row">
                            <PlaceOrderComponent onOrder={() => { placeOrder() }} name={selectedDrink} quantity={selectedQuantity}/>
                        </div>
                    )
                }
             </div>
        )
    }

    function PlaceOrderComponent({onOrder,name, quantity}) {
        return(
            <div className="grid grid-rows-4 grid-flow-col gap-4">
                <h4 className=" "> You have selected {quantity} {name}</h4>
                <button onClick={onOrder} className="  bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded">
                    Order
                </button>
            </div>
        )
    }

    function PreparingDrinkComponent () {
        return (
            <button disabled type="button" class="py-2.5 px-5 mr-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 inline-flex items-center">
                <svg aria-hidden="true" role="status" class="inline w-4 h-4 mr-3 text-gray-200 animate-spin dark:text-gray-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="#1C64F2"/>
                </svg>
                Preparing your order...
            </button>
    )
    }

    function Drink({ orderDrink, selected, image, name, size="100em" }) {
        const borderColor = selected ? 'border-solid border-8  rounded-lg border-indigo-500/50' : '';
        return (
            <div onClick={orderDrink} className={`p-2 m-2 ${borderColor}`}>
                <p>{name}</p>
                <img width={size} src={PATH + image}/>
            </div>
        );
    }

    function ChooseQuantity({ onSelect, max }) {
        const quantities = [1, 2, 3, 4];
        return (
            <div>
                {quantities.map((quantity) => {
                    const disabled = quantity > max;
                    const buttonClass = disabled
                        ? 'text-gray-900 bg-gray-400'
                        : 'text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700';
                    return (
                        <button
                            key={quantity}
                            type="button"
                            onClick={() => onSelect(quantity)}
                            className={`${buttonClass} font-medium rounded-lg text-md px-5 py-2.5 text-center mr-2 mb-2`}
                            disabled={disabled}
                        >
                            {quantity}
                        </button>
                    )
                })}
            </div>
        )
    }

    function OrdersHistoryComponent ()
    {
        const { useEffect, useState } = React;
        const [orders, setOrders] = useState([]);
        const [isLoading, setIsLoading] = useState(true);

        useEffect(() => {
            // Call the orders() function when the component mounts and on subsequent updates
            fetchOrders();
        }, []);

       function fetchOrders() {
            fetch('http://localhost:8080/orders', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
                .then((response) => response.json())
                .then ((data) => {
                    setOrders(data);
                    setIsLoading(false);
                })
                .catch((error) => {
                    // Handle any errors
                    setIsLoading(false);
                    console.error(error);
                });
        }
        if (isLoading) {
            return <div>Loading...</div>; // Render a loading state while the data is being fetched
        }

        return(
            <ul className="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                <li> Orders History</li>
                {orders.map((order) => (
                <li className="py-3 sm:py-4">
                    <div className="flex items-center space-x-4">
                        <div className="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                            </svg>
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-sm font-medium text-gray-900 truncate dark:text-white">
                                order
                            </p>
                            <p className="text-sm text-gray-500 truncate dark:text-gray-400">
                                {order.customer_number}
                            </p>
                        </div>
                        <div className="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                            {order.quantity}
                        </div>
                    </div>
                </li>
                ))}
            </ul>
        )
    }

    class App extends React.Component {
        constructor(props) {
            super(props);
            this.state = {
                ordersHistoryKey: 0, // Initial key value
            };
        }

        handleOrderPlaced = () => {
            // Update the key value to trigger re-mount of OrdersHistoryComponent
            this.setState((prevState) => ({
                ordersHistoryKey: prevState.ordersHistoryKey + 1,
            }));
        };

        render() {
            const { ordersHistoryKey } = this.state;
            return (
                <div>
                    <OrderDrinks onOrderPlaced={this.handleOrderPlaced} />
                    <OrdersHistoryComponent key={ordersHistoryKey} />
                </div>
            )
        }
    }


    // Render your component
    ReactDOM.render(<App />, document.getElementById('react-app'))

</script>

