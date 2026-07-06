export interface User {
  id: number;
  username: string;
  email: string;
  role: 'user' | 'admin';
  fullname: string;
  bio: string;
  avatar: string;
  createdAt: string;
}

export interface Member {
  id: number;
  name: string;
  role: string;
  email: string;
  phone: string;
  bio: string;
  photo: string;
  joinedDate: string;
  createdAt: string;
}

export interface GalleryItem {
  id: number;
  title: string;
  description: string;
  imagePath: string;
  uploadedBy: string;
  category: string;
  isApproved: boolean;
  createdAt: string;
}

export interface Activity {
  id: number;
  title: string;
  description: string;
  imagePath: string;
  activityDate: string;
  createdAt: string;
}

export interface Announcement {
  id: number;
  title: string;
  content: string;
  createdAt: string;
}

export interface ContactMessage {
  id: number;
  name: string;
  email: string;
  subject: string;
  message: string;
  createdAt: string;
  isRead: boolean;
}
