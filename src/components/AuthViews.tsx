import React, { useState } from 'react';
import { User } from '../types';

interface AuthProps {
  onLoginSuccess: (user: User) => void;
  users: User[];
  onRegister: (newUser: User) => void;
  initialView?: 'login' | 'register' | 'forgot';
}

export const AuthViews: React.FC<AuthProps> = ({ onLoginSuccess, users, onRegister, initialView = 'login' }) => {
  const [view, setView] = useState<'login' | 'register' | 'forgot'>(initialView);
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  // Register state
  const [regFullname, setRegFullname] = useState('');
  const [regUsername, setRegUsername] = useState('');
  const [regEmail, setRegEmail] = useState('');
  const [regPass, setRegPass] = useState('');
  const [regConfirmPass, setRegConfirmPass] = useState('');

  // Forgot state
  const [forgotEmail, setForgotEmail] = useState('');
  const [resetToken, setResetToken] = useState('');

  const handleLogin = (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    
    const user = users.find(u => u.username === username || u.email === username);
    if (!user) {
      setError('Invalid username or email address.');
      return;
    }

    // In mock, passwords are user123 or admin123
    if ((user.role === 'admin' && password === 'admin123') || (user.role === 'user' && password === 'password123') || password === 'test123') {
      onLoginSuccess(user);
    } else {
      setError('Invalid credentials supplied.');
    }
  };

  const handleRegister = (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setSuccess('');

    if (!regFullname || !regUsername || !regEmail || !regPass) {
      setError('All fields are required.');
      return;
    }

    if (regPass !== regConfirmPass) {
      setError('Passwords do not match.');
      return;
    }

    if (users.some(u => u.username === regUsername || u.email === regEmail)) {
      setError('Username or email is already registered.');
      return;
    }

    const newUser: User = {
      id: users.length + 1,
      username: regUsername,
      email: regEmail,
      role: 'user',
      fullname: regFullname,
      bio: '',
      avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=200',
      createdAt: new Date().toISOString()
    };

    onRegister(newUser);
    setSuccess('Registration successful! Please sign in with your credentials.');
    setView('login');
    setUsername(regUsername);
  };

  const handleForgot = (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setResetToken('');

    const user = users.find(u => u.email === forgotEmail);
    if (user) {
      const mockToken = Math.random().toString(36).substring(2, 15);
      setResetToken(mockToken);
      setSuccess('Reset link generated!');
    } else {
      setError('No account found with this email.');
    }
  };

  return (
    <div className="max-w-md mx-auto my-12 p-6 md:p-8 bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-100">
      {error && (
        <div className="mb-4 p-3 bg-red-100 dark:bg-red-950/40 border-2 border-red-600 text-red-800 dark:text-red-400 text-xs font-black uppercase tracking-wider text-center">
          {error}
        </div>
      )}
      {success && (
        <div className="mb-4 p-3 bg-sky-100 dark:bg-sky-950/40 border-2 border-sky-600 text-sky-800 dark:text-sky-300 text-xs font-black uppercase tracking-wider text-center">
          {success}
        </div>
      )}

      {view === 'login' && (
        <form onSubmit={handleLogin} className="space-y-4">
          <div className="text-center pb-2 border-b-2 border-slate-900 dark:border-zinc-700 mb-4">
            <h2 className="text-2xl font-black uppercase tracking-tighter text-slate-900 dark:text-white">Account Login</h2>
            <p className="text-xs text-slate-500 dark:text-zinc-400 mt-1 uppercase tracking-wide font-bold">Access your environmental dashboard</p>
          </div>

          <div>
            <label className="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-zinc-300 mb-1">Username or Email</label>
            <input
              type="text"
              className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-sm text-slate-900 dark:text-white"
              placeholder="e.g. admin or john"
              value={username}
              onChange={e => setUsername(e.target.value)}
              required
            />
          </div>

          <div>
            <div className="flex justify-between mb-1">
              <label className="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-zinc-300">Password</label>
              <button type="button" onClick={() => setView('forgot')} className="text-xs text-sky-600 dark:text-sky-400 font-bold uppercase hover:underline">Forgot?</button>
            </div>
            <input
              type="password"
              className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-sm text-slate-900 dark:text-white"
              placeholder="••••••••"
              value={password}
              onChange={e => setPassword(e.target.value)}
              required
            />
          </div>

          <button
            type="submit"
            className="w-full py-3 bg-slate-900 hover:bg-sky-600 dark:bg-zinc-100 dark:text-slate-900 dark:hover:bg-sky-500 text-white font-black uppercase tracking-widest text-xs transition-colors border-2 border-slate-900 dark:border-zinc-100 cursor-pointer"
          >
            Sign In
          </button>

          <div className="text-center text-xs text-slate-500 mt-4 font-bold uppercase tracking-wider">
            Don't have an account?{' '}
            <button type="button" onClick={() => setView('register')} className="text-sky-600 dark:text-sky-400 font-black hover:underline cursor-pointer">Register</button>
          </div>

          <div className="mt-6 p-4 bg-sky-50/50 dark:bg-sky-950/10 border-2 border-slate-900 dark:border-zinc-700">
            <p className="text-xs font-black uppercase tracking-wider text-sky-800 dark:text-sky-400 text-center mb-2">Test Credentials</p>
            <div className="grid grid-cols-1 gap-2 text-[10px] text-slate-600 dark:text-zinc-400 font-mono text-center">
              <div>Admin: <span className="font-bold bg-white dark:bg-zinc-900 px-1 border border-slate-300 dark:border-zinc-700">admin / admin123</span></div>
              <div>User: <span className="font-bold bg-white dark:bg-zinc-900 px-1 border border-slate-300 dark:border-zinc-700">john / password123</span></div>
            </div>
          </div>
        </form>
      )}

      {view === 'register' && (
        <form onSubmit={handleRegister} className="space-y-4">
          <div className="text-center pb-2 border-b-2 border-slate-900 dark:border-zinc-700 mb-4">
            <h2 className="text-2xl font-black uppercase tracking-tighter text-slate-900 dark:text-white">Create Account</h2>
            <p className="text-xs text-slate-500 dark:text-zinc-400 mt-1 uppercase tracking-wide font-bold">Join the research ecosystem</p>
          </div>

          <div>
            <label className="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-zinc-300 mb-1">Full Name</label>
            <input
              type="text"
              className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-sm text-slate-900 dark:text-white"
              placeholder="e.g. Jane Doe"
              value={regFullname}
              onChange={e => setRegFullname(e.target.value)}
              required
            />
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label className="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-zinc-300 mb-1">Username</label>
              <input
                type="text"
                className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-sm text-slate-900 dark:text-white"
                placeholder="jane_doe"
                value={regUsername}
                onChange={e => setRegUsername(e.target.value)}
                required
              />
            </div>
            <div>
              <label className="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-zinc-300 mb-1">Email</label>
              <input
                type="email"
                className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-mono focus:outline-none text-sm text-slate-900 dark:text-white"
                placeholder="jane@example.com"
                value={regEmail}
                onChange={e => setRegEmail(e.target.value)}
                required
              />
            </div>
          </div>

          <div>
            <label className="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-zinc-300 mb-1">Password (Min. 6 chars)</label>
            <input
              type="password"
              className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-sm text-slate-900 dark:text-white"
              placeholder="••••••••"
              value={regPass}
              onChange={e => setRegPass(e.target.value)}
              required
            />
          </div>

          <div>
            <label className="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-zinc-300 mb-1">Confirm Password</label>
            <input
              type="password"
              className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none text-sm text-slate-900 dark:text-white"
              placeholder="••••••••"
              value={regConfirmPass}
              onChange={e => setRegConfirmPass(e.target.value)}
              required
            />
          </div>

          <button
            type="submit"
            className="w-full py-3 bg-slate-900 hover:bg-sky-600 dark:bg-zinc-100 dark:text-slate-900 dark:hover:bg-sky-500 text-white font-black uppercase tracking-widest text-xs transition-colors border-2 border-slate-900 dark:border-zinc-100 cursor-pointer"
          >
            Sign Up
          </button>

          <div className="text-center text-xs text-slate-500 mt-3 font-bold uppercase tracking-wider">
            Already have an account?{' '}
            <button type="button" onClick={() => setView('login')} className="text-sky-600 dark:text-sky-400 font-black hover:underline cursor-pointer">Login</button>
          </div>
        </form>
      )}

      {view === 'forgot' && (
        <form onSubmit={handleForgot} className="space-y-4">
          <div className="text-center pb-2 border-b-2 border-slate-900 dark:border-zinc-700 mb-4">
            <h2 className="text-2xl font-black uppercase tracking-tighter text-slate-900 dark:text-white">Forgot Password</h2>
            <p className="text-xs text-slate-500 dark:text-zinc-400 mt-1 uppercase tracking-wide font-bold">Get local reset code instructions</p>
          </div>

          <div>
            <label className="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-zinc-300 mb-1">Registered Email</label>
            <input
              type="email"
              className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-mono focus:outline-none text-sm text-slate-900 dark:text-white"
              placeholder="john@room320.com"
              value={forgotEmail}
              onChange={e => setForgotEmail(e.target.value)}
              required
            />
          </div>

          <button
            type="submit"
            className="w-full py-3 bg-slate-900 hover:bg-sky-600 dark:bg-zinc-100 dark:text-slate-900 dark:hover:bg-sky-500 text-white font-black uppercase tracking-widest text-xs transition-colors border-2 border-slate-900 dark:border-zinc-100 cursor-pointer"
          >
            Generate Ticket
          </button>

          {resetToken && (
            <div className="p-4 bg-slate-50 dark:bg-zinc-950 border-2 border-slate-900 dark:border-zinc-700 text-xs mt-3">
              <span className="font-black uppercase tracking-wider text-sky-700 dark:text-sky-400 block mb-2">Local reset link bypass (XAMPP emulation):</span>
              <code className="block bg-white dark:bg-black p-2.5 rounded-none text-[10px] break-all select-all border border-slate-300 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 font-mono font-bold">
                http://localhost/Room-No-320-Environment/reset-password.php?token={resetToken}
              </code>
            </div>
          )}

          <div className="text-center font-bold uppercase tracking-wider text-xs">
            <button type="button" onClick={() => setView('login')} className="text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors cursor-pointer">Back to Login</button>
          </div>
        </form>
      )}
    </div>
  );
};
