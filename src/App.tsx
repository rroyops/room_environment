import React, { useState, useEffect } from 'react';
import { User, Member, GalleryItem, Activity, Announcement, ContactMessage } from './types';
import {
  defaultUsers,
  defaultMembers,
  defaultGallery,
  defaultActivities,
  defaultAnnouncements,
  defaultContactMessages
} from './data';
import { AuthViews } from './components/AuthViews';
import { UserPanel } from './components/UserPanel';
import { AdminPanel } from './components/AdminPanel';
import { PublicViews } from './components/PublicViews';

export default function App() {
  // Global States
  const [users, setUsers] = useState<User[]>([]);
  const [members, setMembers] = useState<Member[]>([]);
  const [gallery, setGallery] = useState<GalleryItem[]>([]);
  const [activities, setActivities] = useState<Activity[]>([]);
  const [announcements, setAnnouncements] = useState<Announcement[]>([]);
  const [messages, setMessages] = useState<ContactMessage[]>([]);
  
  // Auth & Navigation State
  const [currentUser, setCurrentUser] = useState<User | null>(null);
  const [currentView, setCurrentView] = useState<'home' | 'about' | 'gallery' | 'members' | 'activities' | 'contact' | 'auth' | 'user' | 'admin'>('home');
  const [darkMode, setDarkMode] = useState(false);

  // Initialize and Sync with Local Storage
  useEffect(() => {
    // Users
    const storedUsers = localStorage.getItem('r320_users');
    if (storedUsers) {
      setUsers(JSON.parse(storedUsers));
    } else {
      setUsers(defaultUsers);
      localStorage.setItem('r320_users', JSON.stringify(defaultUsers));
    }

    // Members
    const storedMembers = localStorage.getItem('r320_members');
    if (storedMembers) {
      setMembers(JSON.parse(storedMembers));
    } else {
      setMembers(defaultMembers);
      localStorage.setItem('r320_members', JSON.stringify(defaultMembers));
    }

    // Gallery
    const storedGallery = localStorage.getItem('r320_gallery');
    if (storedGallery) {
      setGallery(JSON.parse(storedGallery));
    } else {
      setGallery(defaultGallery);
      localStorage.setItem('r320_gallery', JSON.stringify(defaultGallery));
    }

    // Activities
    const storedActivities = localStorage.getItem('r320_activities');
    if (storedActivities) {
      setActivities(JSON.parse(storedActivities));
    } else {
      setActivities(defaultActivities);
      localStorage.setItem('r320_activities', JSON.stringify(defaultActivities));
    }

    // Announcements
    const storedAnnouncements = localStorage.getItem('r320_announcements');
    if (storedAnnouncements) {
      setAnnouncements(JSON.parse(storedAnnouncements));
    } else {
      setAnnouncements(defaultAnnouncements);
      localStorage.setItem('r320_announcements', JSON.stringify(defaultAnnouncements));
    }

    // Messages
    const storedMessages = localStorage.getItem('r320_messages');
    if (storedMessages) {
      setMessages(JSON.parse(storedMessages));
    } else {
      setMessages(defaultContactMessages);
      localStorage.setItem('r320_messages', JSON.stringify(defaultContactMessages));
    }

    // Dark Mode preference
    const storedTheme = localStorage.getItem('r320_theme');
    if (storedTheme === 'dark') {
      setDarkMode(true);
      document.documentElement.classList.add('dark');
    }
  }, []);

  // Theme Toggler helper
  const toggleTheme = () => {
    const nextMode = !darkMode;
    setDarkMode(nextMode);
    if (nextMode) {
      document.documentElement.classList.add('dark');
      localStorage.setItem('r320_theme', 'dark');
    } else {
      document.documentElement.classList.remove('dark');
      localStorage.setItem('r320_theme', 'light');
    }
  };

  // State Modifiers that replicate Database updates locally
  const saveState = (key: string, data: any) => {
    localStorage.setItem(key, JSON.stringify(data));
  };

  const handleRegister = (newUser: User) => {
    const nextList = [...users, newUser];
    setUsers(nextList);
    saveState('r320_users', nextList);
  };

  const handleUpdateProfile = (updatedUser: User) => {
    const nextList = users.map(u => u.id === updatedUser.id ? updatedUser : u);
    setUsers(nextList);
    setCurrentUser(updatedUser);
    saveState('r320_users', nextList);
  };

  // Members CRUD Callbacks
  const handleAddMember = (m: Omit<Member, 'id' | 'createdAt'>) => {
    const newM: Member = {
      ...m,
      id: members.length > 0 ? Math.max(...members.map(item => item.id)) + 1 : 1,
      createdAt: new Date().toISOString()
    };
    const nextList = [newM, ...members];
    setMembers(nextList);
    saveState('r320_members', nextList);
  };

  const handleUpdateMember = (m: Member) => {
    const nextList = members.map(item => item.id === m.id ? m : item);
    setMembers(nextList);
    saveState('r320_members', nextList);
  };

  const handleDeleteMember = (id: number) => {
    const nextList = members.filter(item => item.id !== id);
    setMembers(nextList);
    saveState('r320_members', nextList);
  };

  // Gallery Callbacks
  const handleUploadPhoto = (p: Omit<GalleryItem, 'id' | 'createdAt' | 'isApproved'>) => {
    const newPhoto: GalleryItem = {
      ...p,
      id: gallery.length > 0 ? Math.max(...gallery.map(item => item.id)) + 1 : 1,
      isApproved: false,
      createdAt: new Date().toISOString()
    };
    const nextList = [newPhoto, ...gallery];
    setGallery(nextList);
    saveState('r320_gallery', nextList);
  };

  const handleToggleGalleryApproval = (id: number) => {
    const nextList = gallery.map(item => item.id === id ? { ...item, isApproved: !item.isApproved } : item);
    setGallery(nextList);
    saveState('r320_gallery', nextList);
  };

  const handleDeleteGalleryItem = (id: number) => {
    const nextList = gallery.filter(item => item.id !== id);
    setGallery(nextList);
    saveState('r320_gallery', nextList);
  };

  // Activities Callbacks
  const handleAddActivity = (a: Omit<Activity, 'id' | 'createdAt'>) => {
    const newAct: Activity = {
      ...a,
      id: activities.length > 0 ? Math.max(...activities.map(item => item.id)) + 1 : 1,
      createdAt: new Date().toISOString()
    };
    const nextList = [newAct, ...activities];
    setActivities(nextList);
    saveState('r320_activities', nextList);
  };

  const handleUpdateActivity = (a: Activity) => {
    const nextList = activities.map(item => item.id === a.id ? a : item);
    setActivities(nextList);
    saveState('r320_activities', nextList);
  };

  const handleDeleteActivity = (id: number) => {
    const nextList = activities.filter(item => item.id !== id);
    setActivities(nextList);
    saveState('r320_activities', nextList);
  };

  // Announcements Callbacks
  const handleAddAnnouncement = (an: Omit<Announcement, 'id' | 'createdAt'>) => {
    const newAn: Announcement = {
      ...an,
      id: announcements.length > 0 ? Math.max(...announcements.map(item => item.id)) + 1 : 1,
      createdAt: new Date().toISOString()
    };
    const nextList = [newAn, ...announcements];
    setAnnouncements(nextList);
    saveState('r320_announcements', nextList);
  };

  const handleUpdateAnnouncement = (an: Announcement) => {
    const nextList = announcements.map(item => item.id === an.id ? an : item);
    setAnnouncements(nextList);
    saveState('r320_announcements', nextList);
  };

  const handleDeleteAnnouncement = (id: number) => {
    const nextList = announcements.filter(item => item.id !== id);
    setAnnouncements(nextList);
    saveState('r320_announcements', nextList);
  };

  // Messages Callbacks
  const handleAddMessage = (msg: Omit<ContactMessage, 'id' | 'createdAt' | 'isRead'>) => {
    const newMsg: ContactMessage = {
      ...msg,
      id: messages.length > 0 ? Math.max(...messages.map(item => item.id)) + 1 : 1,
      isRead: false,
      createdAt: new Date().toISOString()
    };
    const nextList = [newMsg, ...messages];
    setMessages(nextList);
    saveState('r320_messages', nextList);
  };

  const handleToggleMessageRead = (id: number) => {
    const nextList = messages.map(item => item.id === id ? { ...item, isRead: !item.isRead } : item);
    setMessages(nextList);
    saveState('r320_messages', nextList);
  };

  const handleDeleteMessage = (id: number) => {
    const nextList = messages.filter(item => item.id !== id);
    setMessages(nextList);
    saveState('r320_messages', nextList);
  };

  return (
    <div className="min-h-screen bg-[#F4F4F5] text-slate-900 dark:bg-zinc-950 dark:text-neutral-100 flex flex-col font-sans transition-colors duration-200">
      
      {/* 📦 XAMPP SOURCE ZIP HERO BAR */}
      <div className="bg-sky-600 dark:bg-sky-700 text-white py-3 px-4 text-center text-xs md:text-sm font-bold border-b-2 border-slate-900 dark:border-zinc-100 relative z-30">
        <div className="max-w-6xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
          <p className="flex items-center gap-2 justify-center">
            <span className="bg-slate-900 text-white text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 border border-slate-700 font-mono">XAMPP READY</span>
            Pure PHP 8 + MySQL source archive is fully bundled and configured!
          </p>
          <a
            href="/api/download"
            download="Room-No-320-Environment.zip"
            className="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-900 text-white hover:bg-white hover:text-slate-900 border-2 border-slate-900 text-xs font-black uppercase tracking-wider transition-colors shrink-0"
          >
            Download ZIP Source
          </a>
        </div>
      </div>

      {/* HEADER SECTION */}
      <header className="sticky top-0 bg-white dark:bg-zinc-900 border-b-2 border-slate-900 dark:border-zinc-100 z-20">
        <div className="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
          
          {/* Logo */}
          <button
            onClick={() => setCurrentView('home')}
            className="flex items-center gap-3 group text-left"
          >
            <div className="w-10 h-10 bg-slate-900 dark:bg-zinc-100 flex items-center justify-center font-black text-white dark:text-zinc-900 text-xl border-2 border-slate-900 dark:border-zinc-100 transition-colors group-hover:bg-sky-600 group-hover:border-sky-600">
              320
            </div>
            <div>
              <span className="font-black tracking-tighter text-lg md:text-xl block text-slate-900 dark:text-white uppercase leading-none">
                ROOM NO. 320 <span className="text-sky-600 dark:text-sky-400">ENVIRONMENT</span>
              </span>
              <span className="text-[9px] text-slate-500 font-bold block mt-0.5 uppercase tracking-widest font-mono">ECO STATION</span>
            </div>
          </button>

          {/* Navigation Links */}
          <nav className="hidden md:flex items-center gap-2">
            {(['home', 'about', 'gallery', 'members', 'activities', 'contact'] as const).map(tab => (
              <button
                key={tab}
                onClick={() => setCurrentView(tab)}
                className={`px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider transition-colors border-2 ${
                  currentView === tab
                    ? 'bg-slate-900 text-white border-slate-900 dark:bg-zinc-100 dark:text-slate-900 dark:border-zinc-100'
                    : 'border-transparent text-slate-600 hover:text-slate-900 dark:text-zinc-400 dark:hover:text-white hover:border-slate-300 dark:hover:border-zinc-700'
                }`}
              >
                {tab}
              </button>
            ))}
          </nav>

          {/* Controls */}
          <div className="flex items-center gap-2">
            {/* Theme Toggle */}
            <button
              onClick={toggleTheme}
              className="p-2 border-2 border-slate-900 dark:border-zinc-300 text-slate-900 dark:text-zinc-100 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
              aria-label="Toggle Theme"
            >
              {darkMode ? '☀️' : '🌙'}
            </button>

            {/* Auth indicator */}
            {currentUser ? (
              <div className="flex items-center gap-2">
                <button
                  onClick={() => setCurrentView(currentUser.role === 'admin' ? 'admin' : 'user')}
                  className="px-4 py-2 bg-slate-900 dark:bg-zinc-100 text-white dark:text-zinc-900 hover:bg-sky-600 dark:hover:bg-sky-500 text-xs font-bold uppercase tracking-wider border-2 border-slate-900 dark:border-zinc-100 transition-colors"
                >
                  Dashboard
                </button>
                <button
                  onClick={() => {
                    setCurrentUser(null);
                    setCurrentView('home');
                  }}
                  className="px-3 py-2 border-2 border-slate-900 dark:border-zinc-300 text-slate-900 dark:text-zinc-300 text-xs font-bold uppercase tracking-wider hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors"
                >
                  Log Out
                </button>
              </div>
            ) : (
              <button
                onClick={() => setCurrentView('auth')}
                className="px-4 py-2 bg-slate-900 dark:bg-zinc-100 text-white dark:text-zinc-900 hover:bg-sky-600 dark:hover:bg-sky-500 text-xs font-bold uppercase tracking-wider border-2 border-slate-900 dark:border-zinc-100 transition-colors"
              >
                Sign In
              </button>
            )}
          </div>
        </div>
      </header>

      {/* MOBILE NAV BAR */}
      <div className="md:hidden flex flex-wrap justify-around border-b-2 border-slate-900 bg-white dark:bg-zinc-900 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:border-zinc-100">
        <button onClick={() => setCurrentView('home')} className={`px-2 py-1 transition-colors ${currentView === 'home' ? 'text-sky-600 dark:text-sky-400 font-extrabold underline decoration-2' : 'hover:text-slate-900 dark:hover:text-white'}`}>Home</button>
        <button onClick={() => setCurrentView('about')} className={`px-2 py-1 transition-colors ${currentView === 'about' ? 'text-sky-600 dark:text-sky-400 font-extrabold underline decoration-2' : 'hover:text-slate-900 dark:hover:text-white'}`}>About</button>
        <button onClick={() => setCurrentView('gallery')} className={`px-2 py-1 transition-colors ${currentView === 'gallery' ? 'text-sky-600 dark:text-sky-400 font-extrabold underline decoration-2' : 'hover:text-slate-900 dark:hover:text-white'}`}>Gallery</button>
        <button onClick={() => setCurrentView('members')} className={`px-2 py-1 transition-colors ${currentView === 'members' ? 'text-sky-600 dark:text-sky-400 font-extrabold underline decoration-2' : 'hover:text-slate-900 dark:hover:text-white'}`}>Members</button>
        <button onClick={() => setCurrentView('activities')} className={`px-2 py-1 transition-colors ${currentView === 'activities' ? 'text-sky-600 dark:text-sky-400 font-extrabold underline decoration-2' : 'hover:text-slate-900 dark:hover:text-white'}`}>Activities</button>
        <button onClick={() => setCurrentView('contact')} className={`px-2 py-1 transition-colors ${currentView === 'contact' ? 'text-sky-600 dark:text-sky-400 font-extrabold underline decoration-2' : 'hover:text-slate-900 dark:hover:text-white'}`}>Contact</button>
      </div>

      {/* MAIN VIEW CONTENT CONTAINER */}
      <main className="flex-1 max-w-6xl w-full mx-auto px-4 py-8">
        {currentView === 'auth' && (
          <AuthViews
            users={users}
            onLoginSuccess={(u) => {
              setCurrentUser(u);
              setCurrentView(u.role === 'admin' ? 'admin' : 'user');
            }}
            onRegister={handleRegister}
          />
        )}

        {currentView === 'user' && currentUser && (
          <UserPanel
            user={currentUser}
            onUpdateProfile={handleUpdateProfile}
            myUploads={gallery.filter(item => item.uploadedBy === currentUser.fullname || item.uploadedBy === currentUser.username)}
          />
        )}

        {currentView === 'admin' && currentUser?.role === 'admin' && (
          <AdminPanel
            members={members}
            onAddMember={handleAddMember}
            onUpdateMember={handleUpdateMember}
            onDeleteMember={handleDeleteMember}
            
            gallery={gallery}
            onToggleGalleryApproval={handleToggleGalleryApproval}
            onDeleteGalleryItem={handleDeleteGalleryItem}
            
            activities={activities}
            onAddActivity={handleAddActivity}
            onUpdateActivity={handleUpdateActivity}
            onDeleteActivity={handleDeleteActivity}
            
            announcements={announcements}
            onAddAnnouncement={handleAddAnnouncement}
            onUpdateAnnouncement={handleUpdateAnnouncement}
            onDeleteAnnouncement={handleDeleteAnnouncement}
            
            messages={messages}
            onToggleMessageRead={handleToggleMessageRead}
            onDeleteMessage={handleDeleteMessage}
            
            usersCount={users.length}
          />
        )}

        {(['home', 'about', 'gallery', 'members', 'activities', 'contact'].includes(currentView)) && (
          <PublicViews
            currentView={currentView as any}
            onNavigate={(view) => setCurrentView(view)}
            members={members}
            gallery={gallery}
            activities={activities}
            announcements={announcements}
            onUploadPhoto={handleUploadPhoto}
            onAddMessage={handleAddMessage}
          />
        )}
      </main>

      {/* FOOTER SECTION */}
      <footer className="bg-white dark:bg-zinc-900 border-t-2 border-slate-900 dark:border-zinc-100 py-8 text-xs text-slate-500 dark:text-zinc-400 mt-16">
        <div className="max-w-6xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-4">
          <div>
            <p className="font-black text-sm text-slate-900 dark:text-white uppercase tracking-tighter">Room No. 320 Environmental Station</p>
            <p className="mt-1">Designed with strict security, responsive metrics, and native PHP / MySQL architecture.</p>
          </div>
          <div className="flex flex-wrap gap-4 font-bold uppercase tracking-wider text-[10px]">
            <button onClick={() => setCurrentView('about')} className="text-slate-900 dark:text-zinc-300 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">About Us</button>
            <button onClick={() => setCurrentView('contact')} className="text-slate-900 dark:text-zinc-300 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">Collaboration Mailbox</button>
            <a href="/api/download" className="text-sky-600 dark:text-sky-400 font-extrabold hover:underline">Download XAMPP Source ZIP</a>
          </div>
        </div>
      </footer>

    </div>
  );
}
