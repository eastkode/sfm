// public/js/script.js

// This file will contain custom JavaScript for animations, dynamic content loading, etc.
// For now, it can remain empty or contain simple console logs.

console.log("script.js loaded!");

// Example: Simple animation for stats counters (will be implemented later)
// function animateCounters() {
//     const counters = document.querySelectorAll('.stat-item h3');
//     counters.forEach(counter => {
//         const target = +counter.innerText.replace(/[^0-9.]/g, '');
//         let current = 0;
//         const increment = target / 200; // Adjust for speed

//         const updateCounter = () => {
//             if (current < target) {
//                 current += increment;
//                 counter.innerText = Math.ceil(current) + '+';
//                 setTimeout(updateCounter, 1);
//             } else {
//                 counter.innerText = target + '+';
//             }
//         };
//         updateCounter();
//     });
// }

// document.addEventListener('DOMContentLoaded', () => {
//     // animateCounters(); // Call this when the stats section is in view
// });
