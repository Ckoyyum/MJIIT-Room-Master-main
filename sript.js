function bookRoom(roomName) {
    alert(`You have booked ${roomName}!`);
}
document.getElementById('quickBook').addEventListener('click', function() {
    window.location.href = 'book.html';
});
