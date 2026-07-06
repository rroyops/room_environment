import express from "express";
import path from "path";
import fs from "fs";
import { createServer as createViteServer } from "vite";

async function startServer() {
  const app = express();
  const PORT = 3000;

  // API Route to handle source code ZIP downloads
  app.get("/api/download", (req, res) => {
    const zipPath = path.resolve(process.cwd(), "Room-No-320-Environment.zip");
    
    if (fs.existsSync(zipPath)) {
      res.setHeader("Content-Disposition", 'attachment; filename="Room-No-320-Environment.zip"');
      res.setHeader("Content-Type", "application/zip");
      const filestream = fs.createReadStream(zipPath);
      filestream.pipe(res);
    } else {
      res.status(404).json({ error: "Source ZIP archive not found on server." });
    }
  });

  // Serve static files / assets if requested
  app.use("/uploads", express.static(path.resolve(process.cwd(), "Room-No-320-Environment/uploads")));

  // Vite middleware for development vs static in production
  if (process.env.NODE_ENV !== "production") {
    const vite = await createViteServer({
      server: { middlewareMode: true },
      appType: "spa",
    });
    app.use(vite.middlewares);
  } else {
    const distPath = path.join(process.cwd(), "dist");
    app.use(express.static(distPath));
    app.get("*", (req, res) => {
      res.sendFile(path.join(distPath, "index.html"));
    });
  }

  app.listen(PORT, "0.0.0.0", () => {
    console.log(`Server running at http://localhost:${PORT}`);
  });
}

startServer();
