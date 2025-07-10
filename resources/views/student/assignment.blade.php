<style>
.profile-bg {
    background: #f5f6fa;
    min-height: 100vh;
    padding: 40px 0;
}
.profile-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    max-width: 420px;
    margin: 40px auto;
    padding: 32px 28px 28px 28px;
}
.profile-title {
    font-size: 2rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 18px;
}
.profile-avatar {
    display: flex;
    justify-content: center;
    margin-bottom: 18px;
}
.profile-avatar img {
    width: 84px;
    height: 84px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e5e7eb;
    background: #f3f4f6;
}
.profile-label {
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
}
.profile-input {
    width: 100%;
    padding: 10px 12px;
    border: 1.5px solid #d1d5db;
    border-radius: 6px;
    margin-bottom: 18px;
    font-size: 1rem;
    background: #f8fafc;
}
.profile-input[disabled], .profile-input[readonly] {
    background: #f3f4f6;
    color: #888;
    cursor: not-allowed;
}
.profile-btn {
    width: 100%;
    background: #4f8cff;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 12px 0;
    font-size: 1.1rem;
    font-weight: 600;
    margin-top: 12px;
    cursor: pointer;
    transition: background 0.2s;
}
.profile-btn:hover {
    background: #2563eb;
}
</style>

<div class="profile-bg">
  <div class="profile-card">
    <div class="profile-title">Profile</div>
    <div class="profile-avatar">
      <img src="https://ui-avatars.com/api/?name=Student&background=cccccc&color=ffffff&size=84" alt="Avatar">
    </div>
    <form>
      <label class="profile-label" for="name">Name</label>
      <input class="profile-input" type="text" id="name" name="name" value="Alex Student" placeholder="Enter your name" disabled>

      <label class="profile-label" for="email">Email</label>
      <input class="profile-input" type="email" id="email" name="email" value="alex@student.com" placeholder="Enter your email" disabled>

      <label class="profile-label" for="studentid">Student Card ID</label>
      <input class="profile-input" type="text" id="studentid" name="studentid" value="2025123456" placeholder="Enter your student card ID">

      <label class="profile-label" for="phone">Phone Number</label>
      <input class="profile-input" type="tel" id="phone" name="phone" value="+1 234 567 8901" placeholder="Enter your phone number">

      <label class="profile-label" for="address">Address</label>
      <input class="profile-input" type="text" id="address" name="address" value="123 Main St, City, Country" placeholder="Enter your address">

      <button type="submit" class="profile-btn">Save Changes</button>
    </form>
  </div>
</div>