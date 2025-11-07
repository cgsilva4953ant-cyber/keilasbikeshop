# 🚀 QUICK START GUIDE - Keila's Bike Shop

## Step 1: Database Setup (Choose ONE method)

### Method A: phpMyAdmin (Easiest - Recommended)
1. Open http://localhost/phpmyadmin
2. Click "New" → Name: `keilas_db` → Click "Create"
3. Click "Import" tab
4. Choose file: `setup.sql` from your project folder
5. Click "Go"
6. Done! ✓

### Method B: PowerShell / Command Line
```powershell
cd C:\xampp\htdocs\keilasbikeshop\keilasbikeshop
& 'C:\xampp\mysql\bin\mysql.exe' -u root -p keilas_db < setup.sql
```
(Press Enter when asked for password if you haven't set one)

## Step 2: Access the Site
Open your browser:
```
http://localhost/keilasbikeshop/keilasbikeshop/
```

## Step 3: Test Login
**Admin Account**
- Email: `admin@keilasbikes.com`
- Password: `admin123`

**Customer Account**
- Email: `juan@example.com`
- Password: `user123`

## 📱 What to Try

### As a Customer:
1. **Browse Products** → Click "Shop" in navbar
2. **View Product** → Click any bike to see details
3. **Add to Cart** → Select quantity and click "Add to Cart"
4. **Checkout** → Click cart icon → "Proceed to Checkout"
5. **View Orders** → User menu → "My Orders"

### As Admin (Future):
- Admin panel coming soon for product/order management

## 🎯 Key Features You Can Test

✅ **Product Catalog** - Browse bikes by category
✅ **Search & Filter** - Find bikes quickly
✅ **Shopping Cart** - Add, update, remove items
✅ **Checkout Process** - Complete order with shipping info
✅ **Order History** - Track your past orders
✅ **User Profile** - Update personal info and password
✅ **Contact Form** - Send inquiries
✅ **Responsive Design** - Try on mobile device

## 📂 Sample Data Included

The setup creates:
- **8 sample bikes** across different categories
- **2 test users** (1 admin, 1 customer)
- All necessary database tables

## 🐛 Common Issues

**"Unknown database 'keilas_db'"**
→ Run the database setup steps above

**"Images not loading"**
→ Make sure bike1.jpg, bike2.jpg, etc. are in the project root folder

**"Session errors"**
→ Make sure you're accessing via `http://localhost/` (not file://)

## 📞 Need Help?

Check the full `README.md` for detailed documentation!

---
**Happy Testing! 🚴‍♀️**
