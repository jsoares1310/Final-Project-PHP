Your time has been assigned to create a full-stack web application for a Hotel. The customer request the following features:

Hotel Customers can search, view and book a room for their desired date.
Hotel customers should not be able to see already occupied rooms within the selected reservation dates.
Hotel customer should be able to leave comments, request specific services (Breakfast, lunch, dinner, parking and etc.),
These services should be available or preferably suggested to the customer at the time of booking.
Hotel customers should be able to fill their wallet when logged into their account.
Hotel staff should be able to approve bookings, add/remove rooms, services, images and all other possible management related to the hotel rooms.
Hotel Admin should be able to add/remove users (staff or customer), unlock a blocked user, and perform the same management as staff.
Security considerations:
Audit generation for the following events: [Authentications, Registrations, Room book approval, and filling the wallet.]
Session in-activity period should be set by the admin and in-active session should be terminated accordingly.
User locking mechanism after 5 unsuccessful attempts. 1st lock for 4 hours, 2nd lock for 10 hours, and 3rd lock permanent only unlocked by the admin.
