import React, { useState } from 'react';
import { User, GalleryItem } from '../types';

interface UserProps {
  user: User;
  onUpdateProfile: (updatedUser: User, pass?: string) => void;
  myUploads: GalleryItem[];
}

export const UserPanel: React.FC<UserProps> = ({ user, onUpdateProfile, myUploads }) => {
  const [activeTab, setActiveTab] = useState<'overview' | 'edit'>('overview');
  const [fullname, setFullname] = useState(user.fullname);
  const [bio, setBio] = useState(user.bio || '');
  const [pass, setPass] = useState('');
  const [confirmPass, setConfirmPass] = useState('');
  const [msg, setMsg] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setMsg(null);

    if (!fullname) {
      setMsg({ type: 'error', text: 'Display Name cannot be empty.' });
      return;
    }

    if (pass && pass !== confirmPass) {
      setMsg({ type: 'error', text: 'New passwords do not match.' });
      return;
    }

    const updatedUser: User = {
      ...user,
      fullname,
      bio
    };

    onUpdateProfile(updatedUser, pass);
    setMsg({ type: 'success', text: 'Profile metrics updated successfully!' });
    setPass('');
    setConfirmPass('');
  };

  return (
    <div className="space-y-8">
      {/* Banner */}
      <div className="bg-slate-900 dark:bg-zinc-950 text-white p-6 border-2 border-slate-900 dark:border-zinc-100 flex flex-wrap justify-between items-center gap-4">
        <div>
          <h2 className="text-2xl font-black uppercase tracking-tighter flex items-center gap-2">
            <span className="w-3 h-3 bg-sky-600 animate-pulse"></span>
            User Research Workspace
          </h2>
          <p className="text-xs text-slate-400 mt-1 uppercase font-bold tracking-wider">Manage bio fields, credentials, and publish contributions.</p>
        </div>
        <div className="flex gap-2">
          <button
            onClick={() => setActiveTab('overview')}
            className={`px-4 py-2 text-xs font-black uppercase tracking-wider transition-colors cursor-pointer border-2 ${
              activeTab === 'overview'
                ? 'bg-sky-600 border-sky-600 text-white'
                : 'bg-white dark:bg-zinc-800 border-slate-900 dark:border-zinc-700 text-slate-900 dark:text-zinc-200'
            }`}
          >
            Overview
          </button>
          <button
            onClick={() => setActiveTab('edit')}
            className={`px-4 py-2 text-xs font-black uppercase tracking-wider transition-colors cursor-pointer border-2 ${
              activeTab === 'edit'
                ? 'bg-sky-600 border-sky-600 text-white'
                : 'bg-white dark:bg-zinc-800 border-slate-900 dark:border-zinc-700 text-slate-900 dark:text-zinc-200'
            }`}
          >
            Edit Profile
          </button>
        </div>
      </div>

      {msg && (
        <div
          className={`p-4 border-2 font-bold uppercase tracking-wide text-xs ${
            msg.type === 'success'
              ? 'bg-sky-50 dark:bg-sky-950/20 border-sky-600 text-sky-800 dark:text-sky-300'
              : 'bg-red-50 dark:bg-red-950/20 border-red-600 text-red-800 dark:text-red-300'
          }`}
        >
          {msg.text}
        </div>
      )}

      {activeTab === 'overview' && (
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {/* Profile Card */}
          <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-6 text-center">
            <div className="w-24 h-24 mx-auto border-4 border-slate-900 dark:border-zinc-300 overflow-hidden bg-slate-100">
              <img src={user.avatar || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=200'} alt="Avatar" className="w-full h-full object-cover" />
            </div>
            <h3 className="text-xl font-black uppercase tracking-tight text-slate-900 dark:text-white mt-4">{user.fullname}</h3>
            <span className="inline-block px-3 py-1 mt-2 text-[10px] font-mono uppercase font-black tracking-wider text-sky-800 dark:text-sky-300 bg-sky-100 dark:bg-sky-950/30 border border-sky-300 dark:border-sky-900 rounded-none">
              {user.role}
            </span>
            <p className="text-xs text-slate-500 dark:text-zinc-400 mt-4 italic font-bold leading-relaxed">
              "{user.bio || 'Enter a short bio description in the Edit tab!'}"
            </p>

            <div className="border-t-2 border-slate-900 dark:border-zinc-700 mt-6 pt-4 space-y-3 text-left text-xs uppercase tracking-wide">
              <div className="flex justify-between text-slate-500">
                <span className="font-bold">Username</span>
                <span className="font-black font-mono text-slate-900 dark:text-white">{user.username}</span>
              </div>
              <div className="flex justify-between text-slate-500">
                <span className="font-bold">Email Address</span>
                <span className="font-black font-mono text-slate-900 dark:text-white">{user.email}</span>
              </div>
              <div className="flex justify-between text-slate-500">
                <span className="font-bold">Member Since</span>
                <span className="font-mono font-black text-slate-900 dark:text-white">{new Date(user.createdAt).toLocaleDateString()}</span>
              </div>
            </div>
          </div>

          {/* Submissions Section */}
          <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-6 md:col-span-2">
            <div className="flex justify-between items-center border-b-2 border-slate-900 dark:border-zinc-700 pb-3 mb-6">
              <h4 className="text-base font-black uppercase tracking-tight text-slate-900 dark:text-white">My Photo Contributions</h4>
              <span className="text-xs font-mono font-black text-slate-500 uppercase">{myUploads.length} total uploads</span>
            </div>

            {myUploads.length === 0 ? (
              <div className="text-center py-16 border-2 border-dashed border-slate-300 dark:border-zinc-800">
                <p className="text-xs text-slate-400 font-bold uppercase tracking-wider">You haven't uploaded any photographs yet.</p>
              </div>
            ) : (
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {myUploads.map(upload => (
                  <div key={upload.id} className="border-2 border-slate-900 dark:border-zinc-700 overflow-hidden bg-white dark:bg-zinc-950">
                    <div className="h-36 bg-slate-100 dark:bg-zinc-800 relative border-b-2 border-slate-900 dark:border-zinc-700">
                      <img src={upload.imagePath} alt="" className="w-full h-full object-cover" />
                      <span className="absolute top-2 left-2 px-2.5 py-0.5 text-[9px] font-mono uppercase font-black tracking-wider border border-slate-900 bg-sky-600 text-white">
                        {upload.category}
                      </span>
                    </div>
                    <div className="p-4">
                      <h5 className="font-black text-sm text-slate-900 dark:text-white truncate uppercase tracking-tight">{upload.title}</h5>
                      <p className="text-xs text-slate-500 dark:text-zinc-400 line-clamp-2 mt-1.5 leading-relaxed">{upload.description}</p>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>
      )}

      {activeTab === 'edit' && (
        <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-6 max-w-2xl">
          <h3 className="text-lg font-black uppercase tracking-tight text-slate-900 dark:text-white mb-6 border-b-2 border-slate-900 dark:border-zinc-700 pb-2">Modify Profile Settings</h3>
          <form onSubmit={handleSubmit} className="space-y-6 text-xs">
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Display Name</label>
                <input
                  type="text"
                  className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-slate-900 dark:text-white"
                  value={fullname}
                  onChange={e => setFullname(e.target.value)}
                  required
                />
              </div>
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Email (Read Only)</label>
                <input
                  type="text"
                  className="w-full p-2.5 bg-slate-100 dark:bg-zinc-800 border-2 border-slate-300 dark:border-zinc-800 text-slate-500 font-mono focus:outline-none"
                  value={user.email}
                  readOnly
                />
              </div>
            </div>

            <div>
              <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Short Bio</label>
              <textarea
                className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-slate-900 dark:text-white"
                rows={4}
                value={bio}
                onChange={e => setBio(e.target.value)}
                placeholder="Explain your eco sectors, solar feeds, active air filters..."
              />
            </div>

            <h4 className="text-sm font-black uppercase tracking-tight text-slate-900 dark:text-white pt-4 border-t-2 border-slate-900 dark:border-zinc-700">Change password (optional)</h4>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">New Password</label>
                <input
                  type="password"
                  className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-mono focus:outline-none text-slate-900 dark:text-white"
                  value={pass}
                  onChange={e => setPass(e.target.value)}
                  placeholder="Leave empty to retain"
                />
              </div>
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Confirm New Password</label>
                <input
                  type="password"
                  className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-mono focus:outline-none text-slate-900 dark:text-white"
                  value={confirmPass}
                  onChange={e => setConfirmPass(e.target.value)}
                  placeholder="Repeat password"
                />
              </div>
            </div>

            <div className="pt-4 flex gap-3">
              <button
                type="submit"
                className="px-6 py-3 bg-slate-900 hover:bg-sky-600 dark:bg-zinc-100 dark:text-slate-900 dark:hover:bg-sky-500 text-white font-black uppercase tracking-widest text-xs transition-colors border-2 border-slate-900 dark:border-zinc-100 cursor-pointer"
              >
                Save Settings
              </button>
              <button
                type="button"
                onClick={() => { setFullname(user.fullname); setBio(user.bio || ''); }}
                className="px-6 py-3 border-2 border-slate-900 text-slate-900 hover:bg-slate-100 dark:border-zinc-500 dark:text-zinc-300 dark:hover:bg-zinc-800 text-xs font-black uppercase tracking-widest cursor-pointer"
              >
                Reset Form
              </button>
            </div>
          </form>
        </div>
      )}
    </div>
  );
};
