<!DOCTYPE html>
<html>
<body>
  <form action="{{ url('/supplier/raw-materials/checkout') }}" method="POST">
    @csrf
    <input type="text" name="shipping_address" value="Test Address" required>
    <input type="text" name="billing_address" value="Test Address" required>
    <button type="submit">Test Place Order</button>
  </form>
</body>
</html> 