import "./bootstrap";

console.log("✅ app.js loaded");

window.RealtimeRegistry = {};

window.registerRealtime = function (entity, callback) {
    if (!window.RealtimeRegistry[entity]) {
        window.RealtimeRegistry[entity] = [];
    }
    window.RealtimeRegistry[entity].push(callback);
};

window.Echo.channel("data-changed").listen(".changed", (e) => {
    const myId = window.authKaryawanId;
    if (e.user_id === myId) return;

    const handlers = window.RealtimeRegistry[e.entity];
    if (!handlers) return;

    handlers.forEach((fn) => fn());
});
