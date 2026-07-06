import React, { useState } from 'react';
import { Member, GalleryItem, Activity, Announcement, ContactMessage, User } from '../types';

interface AdminProps {
  members: Member[];
  onAddMember: (m: Omit<Member, 'id' | 'createdAt'>) => void;
  onUpdateMember: (m: Member) => void;
  onDeleteMember: (id: number) => void;

  gallery: GalleryItem[];
  onToggleGalleryApproval: (id: number) => void;
  onDeleteGalleryItem: (id: number) => void;

  activities: Activity[];
  onAddActivity: (a: Omit<Activity, 'id' | 'createdAt'>) => void;
  onUpdateActivity: (a: Activity) => void;
  onDeleteActivity: (id: number) => void;

  announcements: Announcement[];
  onAddAnnouncement: (an: Omit<Announcement, 'id' | 'createdAt'>) => void;
  onUpdateAnnouncement: (an: Announcement) => void;
  onDeleteAnnouncement: (id: number) => void;

  messages: ContactMessage[];
  onToggleMessageRead: (id: number) => void;
  onDeleteMessage: (id: number) => void;

  usersCount: number;
}

export const AdminPanel: React.FC<AdminProps> = ({
  members, onAddMember, onUpdateMember, onDeleteMember,
  gallery, onToggleGalleryApproval, onDeleteGalleryItem,
  activities, onAddActivity, onUpdateActivity, onDeleteActivity,
  announcements, onAddAnnouncement, onUpdateAnnouncement, onDeleteAnnouncement,
  messages, onToggleMessageRead, onDeleteMessage,
  usersCount
}) => {
  type Tab = 'dashboard' | 'members' | 'gallery' | 'activities' | 'announcements' | 'messages';
  const [activeTab, setActiveTab] = useState<Tab>('dashboard');

  // Form states - Member
  const [mName, setMName] = useState('');
  const [mRole, setMRole] = useState('');
  const [mEmail, setMEmail] = useState('');
  const [mPhone, setMPhone] = useState('');
  const [mBio, setMBio] = useState('');
  const [mJoined, setMJoined] = useState('');
  const [editingMember, setEditingMember] = useState<Member | null>(null);

  // Form states - Activity
  const [aTitle, setATitle] = useState('');
  const [aDesc, setADesc] = useState('');
  const [aDate, setADate] = useState('');
  const [editingActivity, setEditingActivity] = useState<Activity | null>(null);

  // Form states - Announcement
  const [anTitle, setAnTitle] = useState('');
  const [anContent, setAnContent] = useState('');
  const [editingAnnouncement, setEditingAnnouncement] = useState<Announcement | null>(null);

  const resetMemberForm = () => {
    setMName(''); setMRole(''); setMEmail(''); setMPhone(''); setMBio(''); setMJoined('');
    setEditingMember(null);
  };

  const resetActivityForm = () => {
    setATitle(''); setADesc(''); setADate('');
    setEditingActivity(null);
  };

  const resetAnnouncementForm = () => {
    setAnTitle(''); setAnContent('');
    setEditingAnnouncement(null);
  };

  return (
    <div className="space-y-8">
      {/* Admin Nav */}
      <div className="bg-slate-900 dark:bg-zinc-950 text-white p-6 border-2 border-slate-900 dark:border-zinc-100 flex flex-wrap justify-between items-center gap-4">
        <div>
          <h2 className="text-2xl font-black uppercase tracking-tighter flex items-center gap-2">
            <span className="w-3 h-3 bg-red-600 animate-pulse"></span>
            Management Console
          </h2>
          <p className="text-xs text-slate-400 mt-1 uppercase font-bold tracking-wider">Control community ledgers, moderate gallery uploads, and respond to inquiries.</p>
        </div>

        <div className="flex flex-wrap gap-1 bg-slate-800 p-1 border-2 border-slate-700">
          {(['dashboard', 'members', 'gallery', 'activities', 'announcements', 'messages'] as Tab[]).map(tab => (
            <button
              key={tab}
              onClick={() => setActiveTab(tab)}
              className={`px-4 py-2 text-xs font-black uppercase tracking-wider transition-colors cursor-pointer ${
                activeTab === tab
                  ? 'bg-sky-600 text-white'
                  : 'text-slate-300 hover:text-white hover:bg-slate-700'
              }`}
            >
              {tab}
            </button>
          ))}
        </div>
      </div>

      {/* DASHBOARD TAB */}
      {activeTab === 'dashboard' && (
        <div className="space-y-8">
          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-4 text-center">
              <span className="text-3xl font-black text-slate-900 dark:text-white font-mono">{usersCount}</span>
              <span className="block text-[10px] text-slate-400 dark:text-zinc-500 font-black uppercase tracking-wider mt-1">Users</span>
            </div>
            <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-4 text-center">
              <span className="text-3xl font-black text-slate-900 dark:text-white font-mono">{members.length}</span>
              <span className="block text-[10px] text-slate-400 dark:text-zinc-500 font-black uppercase tracking-wider mt-1">Members</span>
            </div>
            <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-4 text-center">
              <span className="text-3xl font-black text-slate-900 dark:text-white font-mono">{gallery.length}</span>
              <span className="block text-[10px] text-slate-400 dark:text-zinc-500 font-black uppercase tracking-wider mt-1">Photos</span>
            </div>
            <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-4 text-center">
              <span className="text-3xl font-black text-slate-900 dark:text-white font-mono">{activities.length}</span>
              <span className="block text-[10px] text-slate-400 dark:text-zinc-500 font-black uppercase tracking-wider mt-1">Activities</span>
            </div>
            <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-4 text-center">
              <span className="text-3xl font-black text-slate-900 dark:text-white font-mono">{announcements.length}</span>
              <span className="block text-[10px] text-slate-400 dark:text-zinc-500 font-black uppercase tracking-wider mt-1">Bulletins</span>
            </div>
            <div className="bg-sky-100 dark:bg-sky-950/40 border-2 border-sky-600 p-4 text-center">
              <span className="text-3xl font-black text-sky-800 dark:text-sky-300 font-mono">
                {messages.filter(m => !m.isRead).length}
              </span>
              <span className="block text-[10px] text-sky-700 dark:text-sky-400 font-black uppercase tracking-wider mt-1">Unread Mail</span>
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            {/* Quick Actions */}
            <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-6">
              <h3 className="font-black uppercase tracking-tight text-base text-slate-900 dark:text-white mb-4 border-b-2 border-slate-900 dark:border-zinc-700 pb-2 flex items-center gap-2">
                <span className="w-2.5 h-2.5 bg-sky-600"></span> Quick Navigation Shortcuts
              </h3>
              <div className="grid grid-cols-2 gap-4">
                <button onClick={() => setActiveTab('members')} className="p-4 text-left border-2 border-slate-900 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800/30 transition-colors cursor-pointer">
                  <span className="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Manage Members</span>
                  <span className="block text-[10px] text-slate-500 mt-1">Add or remove advisors</span>
                </button>
                <button onClick={() => setActiveTab('gallery')} className="p-4 text-left border-2 border-slate-900 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800/30 transition-colors cursor-pointer">
                  <span className="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Moderate Gallery</span>
                  <span className="block text-[10px] text-slate-500 mt-1">Approve student images</span>
                </button>
                <button onClick={() => setActiveTab('activities')} className="p-4 text-left border-2 border-slate-900 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800/30 transition-colors cursor-pointer">
                  <span className="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Manage Activities</span>
                  <span className="block text-[10px] text-slate-500 mt-1">Log carbon/planting drives</span>
                </button>
                <button onClick={() => setActiveTab('messages')} className="p-4 text-left border-2 border-sky-600 bg-sky-50/20 dark:bg-sky-950/5 hover:bg-sky-50 dark:hover:bg-sky-950/10 transition-colors cursor-pointer">
                  <span className="text-xs font-black uppercase tracking-wider text-sky-800 dark:text-sky-400">Review Inquiries</span>
                  <span className="block text-[10px] text-sky-600 mt-1">Check student proposals</span>
                </button>
              </div>
            </div>

            {/* Unread message teasers */}
            <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-6">
              <div className="flex justify-between items-center mb-4 border-b-2 border-slate-900 dark:border-zinc-700 pb-2">
                <h3 className="font-black uppercase tracking-tight text-base text-slate-900 dark:text-white flex items-center gap-2">
                  <span className="w-2.5 h-2.5 bg-sky-600"></span> Recent Message Submissions
                </h3>
                <button onClick={() => setActiveTab('messages')} className="text-xs text-sky-600 dark:text-sky-400 font-black uppercase hover:underline cursor-pointer">Inbox</button>
              </div>
              
              {messages.length === 0 ? (
                <p className="text-xs text-slate-400 text-center py-12 font-mono uppercase tracking-wider">No contact inquiries exist.</p>
              ) : (
                <div className="space-y-4">
                  {messages.slice(0, 3).map(m => (
                    <div key={m.id} className={`p-4 border-2 text-xs ${!m.isRead ? 'bg-slate-50 dark:bg-zinc-850/50 border-slate-900 dark:border-zinc-300' : 'border-slate-200 dark:border-zinc-800'}`}>
                      <div className="flex justify-between">
                        <span className="font-black uppercase text-slate-900 dark:text-white">{m.name}</span>
                        <span className="text-[10px] font-mono text-slate-400">{new Date(m.createdAt).toLocaleDateString()}</span>
                      </div>
                      <div className="font-black text-sky-700 dark:text-sky-400 mt-1 uppercase tracking-tight">{m.subject}</div>
                      <p className="text-slate-500 dark:text-zinc-400 line-clamp-1 mt-1 leading-relaxed">{m.message}</p>
                    </div>
                  ))}
                </div>
              )}
            </div>
          </div>
        </div>
      )}

      {/* MEMBERS TAB */}
      {activeTab === 'members' && (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Member Form Card */}
          <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-5 h-fit">
            <h3 className="font-black uppercase tracking-tight text-sm text-slate-900 dark:text-white mb-4 border-b-2 border-slate-900 dark:border-zinc-700 pb-2 flex items-center gap-2">
              <span className="w-2.5 h-2.5 bg-sky-600"></span> {editingMember ? 'Edit Member Profile' : 'Add New Member'}
            </h3>
            <form onSubmit={e => {
              e.preventDefault();
              if (editingMember) {
                onUpdateMember({
                  ...editingMember,
                  name: mName,
                  role: mRole,
                  email: mEmail,
                  phone: mPhone,
                  bio: mBio,
                  joinedDate: mJoined
                });
              } else {
                onAddMember({ name: mName, role: mRole, email: mEmail, phone: mPhone, bio: mBio, joinedDate: mJoined, photo: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=200' });
              }
              resetMemberForm();
            }} className="space-y-4 text-xs">
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Full Name</label>
                <input type="text" className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-slate-900 dark:text-white" value={mName} onChange={e => setMName(e.target.value)} required />
              </div>
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Title / Role</label>
                <input type="text" className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-slate-900 dark:text-white" value={mRole} onChange={e => setMRole(e.target.value)} required />
              </div>
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Email Address</label>
                <input type="email" className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-mono focus:outline-none text-slate-900 dark:text-white" value={mEmail} onChange={e => setMEmail(e.target.value)} required />
              </div>
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Phone</label>
                <input type="text" className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-mono focus:outline-none text-slate-900 dark:text-white" value={mPhone} onChange={e => setMPhone(e.target.value)} />
              </div>
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Joined Date</label>
                <input type="date" className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-mono focus:outline-none text-slate-900 dark:text-white" value={mJoined} onChange={e => setMJoined(e.target.value)} required />
              </div>
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Researcher Bio</label>
                <textarea className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-slate-900 dark:text-white" rows={3} value={mBio} onChange={e => setMBio(e.target.value)} required />
              </div>

              <div className="flex gap-2 pt-2">
                <button type="submit" className="flex-1 py-3 bg-slate-900 hover:bg-sky-600 dark:bg-zinc-100 dark:text-slate-900 dark:hover:bg-sky-500 text-white font-black uppercase tracking-widest text-xs transition-colors border-2 border-slate-900 dark:border-zinc-100 cursor-pointer">
                  {editingMember ? 'Save Updates' : 'Add Member'}
                </button>
                {editingMember && (
                  <button type="button" onClick={resetMemberForm} className="py-3 px-4 border-2 border-slate-900 text-slate-900 hover:bg-slate-100 dark:border-zinc-500 dark:text-zinc-300 dark:hover:bg-zinc-800 text-xs font-black uppercase tracking-widest cursor-pointer">
                    Cancel
                  </button>
                )}
              </div>
            </form>
          </div>

          {/* Members Table */}
          <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-5 lg:col-span-2 overflow-x-auto">
            <h3 className="font-black uppercase tracking-tight text-sm text-slate-900 dark:text-white mb-4 border-b-2 border-slate-900 dark:border-zinc-700 pb-2 flex items-center gap-2">
              <span className="w-2.5 h-2.5 bg-sky-600"></span> Active Members Registry
            </h3>
            <table className="w-full text-left border-collapse text-xs">
              <thead>
                <tr className="border-b-2 border-slate-900 dark:border-zinc-700 text-slate-400 font-black uppercase tracking-wider">
                  <th className="py-3">Name</th>
                  <th className="py-3">Role</th>
                  <th className="py-3">Email</th>
                  <th className="py-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100 dark:divide-zinc-800/50 font-bold">
                {members.map(m => (
                  <tr key={m.id} className="text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-850/20">
                    <td className="py-3.5 font-black text-slate-900 dark:text-white uppercase tracking-tight">{m.name}</td>
                    <td className="py-3.5">
                      <span className="px-2 py-0.5 bg-sky-100 dark:bg-sky-950 text-sky-800 dark:text-sky-300 border border-sky-300 dark:border-sky-900 rounded-none text-[9px] font-mono uppercase font-black tracking-wider">
                        {m.role}
                      </span>
                    </td>
                    <td className="py-3.5 font-mono text-xs">{m.email}</td>
                    <td className="py-3.5 text-right space-x-3 text-xs uppercase tracking-wider font-black">
                      <button onClick={() => {
                        setEditingMember(m);
                        setMName(m.name); setMRole(m.role); setMEmail(m.email); setMPhone(m.phone || ''); setMBio(m.bio); setMJoined(m.joinedDate);
                      }} className="text-sky-600 dark:text-sky-400 hover:underline cursor-pointer">Edit</button>
                      <button onClick={() => onDeleteMember(m.id)} className="text-red-500 hover:underline cursor-pointer">Delete</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* GALLERY TAB */}
      {activeTab === 'gallery' && (
        <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-5">
          <h3 className="font-black uppercase tracking-tight text-sm text-slate-900 dark:text-white mb-6 border-b-2 border-slate-900 dark:border-zinc-700 pb-2 flex items-center gap-2">
            <span className="w-2.5 h-2.5 bg-sky-600"></span> Gallery Submissions Moderator
          </h3>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {gallery.map(g => (
              <div key={g.id} className="border-2 border-slate-900 dark:border-zinc-700 flex flex-col justify-between bg-white dark:bg-zinc-950">
                <div className="h-40 bg-slate-100 dark:bg-zinc-800 relative border-b-2 border-slate-900 dark:border-zinc-700">
                  <img src={g.imagePath} alt="" className="w-full h-full object-cover" />
                  <span className={`absolute top-2 right-2 px-2 py-0.5 text-[9px] font-mono uppercase font-black tracking-wider border-2 border-slate-900 ${g.isApproved ? 'bg-sky-600 text-white' : 'bg-amber-500 text-slate-900'}`}>
                    {g.isApproved ? 'Approved' : 'Retracted'}
                  </span>
                </div>
                <div className="p-4 flex-1 flex flex-col justify-between space-y-4">
                  <div>
                    <h5 className="font-black uppercase tracking-tight text-slate-900 dark:text-white text-sm">{g.title}</h5>
                    <p className="text-xs text-slate-500 dark:text-zinc-400 mt-1.5 leading-relaxed">{g.description}</p>
                    <span className="text-[10px] text-slate-400 block mt-3 font-mono font-bold uppercase">Uploaded By: {g.uploadedBy}</span>
                  </div>
                  <div className="flex gap-2 border-t border-slate-100 dark:border-zinc-800 pt-3">
                    <button
                      onClick={() => onToggleGalleryApproval(g.id)}
                      className={`flex-1 py-2 text-xs font-black uppercase tracking-wider border-2 border-slate-900 cursor-pointer ${g.isApproved ? 'bg-amber-100 hover:bg-amber-200 text-amber-800' : 'bg-sky-100 hover:bg-sky-200 text-sky-800'}`}
                    >
                      {g.isApproved ? 'Retract' : 'Approve'}
                    </button>
                    <button
                      onClick={() => onDeleteGalleryItem(g.id)}
                      className="py-2 px-4 bg-red-100 hover:bg-red-200 text-red-800 text-xs font-black uppercase tracking-wider border-2 border-slate-900 cursor-pointer"
                    >
                      Delete
                    </button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* ACTIVITIES TAB */}
      {activeTab === 'activities' && (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Add Form */}
          <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-5 h-fit">
            <h3 className="font-black uppercase tracking-tight text-sm text-slate-900 dark:text-white mb-4 border-b-2 border-slate-900 dark:border-zinc-700 pb-2 flex items-center gap-2">
              <span className="w-2.5 h-2.5 bg-sky-600"></span> {editingActivity ? 'Edit Activity Log' : 'Log New Activity'}
            </h3>
            <form onSubmit={e => {
              e.preventDefault();
              if (editingActivity) {
                onUpdateActivity({
                  ...editingActivity,
                  title: aTitle,
                  description: aDesc,
                  activityDate: aDate
                });
              } else {
                onAddActivity({ title: aTitle, description: aDesc, activityDate: aDate, imagePath: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80&w=600' });
              }
              resetActivityForm();
            }} className="space-y-4 text-xs">
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Activity Title</label>
                <input type="text" className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-slate-900 dark:text-white" value={aTitle} onChange={e => setATitle(e.target.value)} required />
              </div>
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Activity Date</label>
                <input type="date" className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-mono focus:outline-none text-slate-900 dark:text-white" value={aDate} onChange={e => setADate(e.target.value)} required />
              </div>
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Description Details</label>
                <textarea className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-slate-900 dark:text-white" rows={5} value={aDesc} onChange={e => setADesc(e.target.value)} required />
              </div>

              <div className="flex gap-2 pt-2">
                <button type="submit" className="flex-1 py-3 bg-slate-900 hover:bg-sky-600 dark:bg-zinc-100 dark:text-slate-900 dark:hover:bg-sky-500 text-white font-black uppercase tracking-widest text-xs transition-colors border-2 border-slate-900 dark:border-zinc-100 cursor-pointer">
                  {editingActivity ? 'Save Updates' : 'Publish Log'}
                </button>
                {editingActivity && (
                  <button type="button" onClick={resetActivityForm} className="py-3 px-4 border-2 border-slate-900 text-slate-900 hover:bg-slate-100 dark:border-zinc-500 dark:text-zinc-300 dark:hover:bg-zinc-800 text-xs font-black uppercase tracking-widest cursor-pointer">
                    Cancel
                  </button>
                )}
              </div>
            </form>
          </div>

          {/* Activities List */}
          <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-5 lg:col-span-2 overflow-x-auto">
            <h3 className="font-black uppercase tracking-tight text-sm text-slate-900 dark:text-white mb-4 border-b-2 border-slate-900 dark:border-zinc-700 pb-2 flex items-center gap-2">
              <span className="w-2.5 h-2.5 bg-sky-600"></span> Initiatives & Sprints Log
            </h3>
            <table className="w-full text-left border-collapse text-xs">
              <thead>
                <tr className="border-b-2 border-slate-900 dark:border-zinc-700 text-slate-400 font-black uppercase tracking-wider">
                  <th className="py-3">Title</th>
                  <th className="py-3">Date</th>
                  <th className="py-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100 dark:divide-zinc-800/50 font-bold">
                {activities.map(a => (
                  <tr key={a.id} className="text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-850/20">
                    <td className="py-3.5 font-black text-slate-900 dark:text-white uppercase tracking-tight">{a.title}</td>
                    <td className="py-3.5 font-mono text-slate-500">{a.activityDate}</td>
                    <td className="py-3.5 text-right space-x-3 text-xs uppercase tracking-wider font-black">
                      <button onClick={() => {
                        setEditingActivity(a);
                        setATitle(a.title); setADesc(a.description); setADate(a.activityDate);
                      }} className="text-sky-600 dark:text-sky-400 hover:underline cursor-pointer">Edit</button>
                      <button onClick={() => onDeleteActivity(a.id)} className="text-red-500 hover:underline cursor-pointer">Delete</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* ANNOUNCEMENTS TAB */}
      {activeTab === 'announcements' && (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Form */}
          <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-5 h-fit">
            <h3 className="font-black uppercase tracking-tight text-sm text-slate-900 dark:text-white mb-4 border-b-2 border-slate-900 dark:border-zinc-700 pb-2 flex items-center gap-2">
              <span className="w-2.5 h-2.5 bg-sky-600"></span> {editingAnnouncement ? 'Edit Bulletin' : 'Broadcast Bulletin'}
            </h3>
            <form onSubmit={e => {
              e.preventDefault();
              if (editingAnnouncement) {
                onUpdateAnnouncement({
                  ...editingAnnouncement,
                  title: anTitle,
                  content: anContent
                });
              } else {
                onAddAnnouncement({ title: anTitle, content: anContent });
              }
              resetAnnouncementForm();
            }} className="space-y-4 text-xs">
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Bulletin Title</label>
                <input type="text" className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-slate-900 dark:text-white" value={anTitle} onChange={e => setAnTitle(e.target.value)} required />
              </div>
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Content Text</label>
                <textarea className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-slate-900 dark:text-white" rows={6} value={anContent} onChange={e => setAnContent(e.target.value)} required />
              </div>

              <div className="flex gap-2 pt-2">
                <button type="submit" className="flex-1 py-3 bg-slate-900 hover:bg-sky-600 dark:bg-zinc-100 dark:text-slate-900 dark:hover:bg-sky-500 text-white font-black uppercase tracking-widest text-xs transition-colors border-2 border-slate-900 dark:border-zinc-100 cursor-pointer">
                  {editingAnnouncement ? 'Save Updates' : 'Publish News'}
                </button>
                {editingAnnouncement && (
                  <button type="button" onClick={resetAnnouncementForm} className="py-3 px-4 border-2 border-slate-900 text-slate-900 hover:bg-slate-100 dark:border-zinc-500 dark:text-zinc-300 dark:hover:bg-zinc-800 text-xs font-black uppercase tracking-widest cursor-pointer">
                    Cancel
                  </button>
                )}
              </div>
            </form>
          </div>

          {/* List */}
          <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-5 lg:col-span-2 overflow-x-auto">
            <h3 className="font-black uppercase tracking-tight text-sm text-slate-900 dark:text-white mb-4 border-b-2 border-slate-900 dark:border-zinc-700 pb-2 flex items-center gap-2">
              <span className="w-2.5 h-2.5 bg-sky-600"></span> Official Community Bulletins
            </h3>
            <table className="w-full text-left border-collapse text-xs">
              <thead>
                <tr className="border-b-2 border-slate-900 dark:border-zinc-700 text-slate-400 font-black uppercase tracking-wider">
                  <th className="py-3">Title</th>
                  <th className="py-3">Date Published</th>
                  <th className="py-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100 dark:divide-zinc-800/50 font-bold">
                {announcements.map(an => (
                  <tr key={an.id} className="text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-850/20">
                    <td className="py-3.5 font-black text-slate-900 dark:text-white uppercase tracking-tight">{an.title}</td>
                    <td className="py-3.5 font-mono text-slate-500">{new Date(an.createdAt).toLocaleDateString()}</td>
                    <td className="py-3.5 text-right space-x-3 text-xs uppercase tracking-wider font-black">
                      <button onClick={() => {
                        setEditingAnnouncement(an);
                        setAnTitle(an.title); setAnContent(an.content);
                      }} className="text-sky-600 dark:text-sky-400 hover:underline cursor-pointer">Edit</button>
                      <button onClick={() => onDeleteAnnouncement(an.id)} className="text-red-500 hover:underline cursor-pointer">Delete</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* MESSAGES TAB */}
      {activeTab === 'messages' && (
        <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-5">
          <h3 className="font-black uppercase tracking-tight text-sm text-slate-900 dark:text-white mb-4 border-b-2 border-slate-900 dark:border-zinc-700 pb-2 flex items-center gap-2">
            <span className="w-2.5 h-2.5 bg-sky-600"></span> Correspondence Mailbox
          </h3>
          {messages.length === 0 ? (
            <div className="text-center py-12 border-2 border-dashed border-slate-300 dark:border-zinc-800">
              <p className="text-slate-400 text-sm uppercase tracking-wider font-bold">Inbox is completely clean.</p>
            </div>
          ) : (
            <div className="divide-y divide-slate-200 dark:divide-zinc-800">
              {messages.map(m => (
                <div key={m.id} className={`py-5 flex flex-wrap justify-between items-start gap-4 ${!m.isRead ? 'font-bold text-slate-900 dark:text-white' : 'text-slate-600 dark:text-zinc-400'}`}>
                  <div className="space-y-1.5 max-w-xl flex-1">
                    <div className="flex flex-wrap items-center gap-2">
                      <span className="text-sm font-black uppercase tracking-tight">{m.name}</span>
                      <span className="text-xs font-mono text-sky-600 dark:text-sky-400 font-bold">({m.email})</span>
                      <span className="text-[10px] text-slate-400 font-mono">/ {new Date(m.createdAt).toLocaleString()}</span>
                    </div>
                    <div className="text-sky-700 dark:text-sky-300 text-xs font-mono uppercase font-black bg-sky-50 dark:bg-sky-950/55 px-2 py-0.5 border border-sky-300 dark:border-sky-900 inline-block">{m.subject}</div>
                    <p className="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed font-sans">{m.message}</p>
                  </div>

                  <div className="flex gap-2 text-[10px] font-black uppercase tracking-wider font-mono">
                    <button
                      onClick={() => onToggleMessageRead(m.id)}
                      className={`px-3 py-1.5 border-2 border-slate-900 cursor-pointer ${m.isRead ? 'bg-slate-50 hover:bg-slate-100 text-slate-600' : 'bg-sky-100 hover:bg-sky-200 text-sky-800'}`}
                    >
                      {m.isRead ? 'Mark Unread' : 'Mark Read'}
                    </button>
                    <button
                      onClick={() => onDeleteMessage(m.id)}
                      className="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-800 border-2 border-slate-900 cursor-pointer"
                    >
                      Delete
                    </button>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      )}
    </div>
  );
};
