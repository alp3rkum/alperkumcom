//PHPIFY v2 (ReactJS)

const start = process.hrtime.bigint();

const fs = require("fs");
const path = require("path");

const outDir = path.join(__dirname, "..", "dist");

const phpSrc = path.join(__dirname, "..", "src", "php");
const phpDest = outDir; // doğrudan out içine

// Asenkron recursive copy (concurrency limit)
async function copyRecursiveAsync(src, dest, concurrency = 2) {
  const queue = [];
  let active = 0;

  async function worker(task) {
    active++;
    try {
      await task();
    } finally {
      active--;
      if (queue.length > 0) {
        const next = queue.shift();
        worker(next);
      }
    }
  }

  async function enqueue(task) {
    if (active < concurrency) {
      worker(task);
    } else {
      queue.push(task);
    }
  }

  async function processDir(srcDir, destDir) {
    await fs.promises.mkdir(destDir, { recursive: true });
    const files = await fs.promises.readdir(srcDir);

    for (const file of files) {
      const srcPath = path.join(srcDir, file);
      const destPath = path.join(destDir, file);
      const stat = await fs.promises.lstat(srcPath);

      if (stat.isDirectory()) {
        await processDir(srcPath, destPath);
      } else {
        await enqueue(async () => {
          await fs.promises.copyFile(srcPath, destPath);
        });
      }
    }
  }

  await processDir(src, dest);

  // Kuyruğun bitmesini bekle
  while (active > 0 || queue.length > 0) {
    await new Promise((r) => setTimeout(r, 50));
  }
}

(async () => {
  await copyRecursiveAsync(phpSrc, phpDest, 4);
  console.log("✅ src/php içeriği dist içine paralel kopyalandı.");

  const end = process.hrtime.bigint();
  const durationMs = Number(end - start) / 1e9; // ms cinsinden
  console.log(`⏱️ Toplam süre: ${durationMs.toFixed(2)} s`);
})();