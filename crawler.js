
const axios = require('axios');
const cheerio = require('cheerio');
const fs = require('fs');
const { URL } = require('url');

class WebCrawler {
  constructor(config = {}) {
    this.baseUrl = config.baseUrl;
    this.selector = config.selector || 'body'; // 默认选择器
    this.outputDir = config.outputDir || './articles/';
    this.visitedUrls = new Set();
  }

  async crawl(url) {
    try {
      // 防止重复爬取
      if (this.visitedUrls.has(url)) return;
      this.visitedUrls.add(url);

      // 发送 HTTP 请求
      const response = await axios.get(url, {
        headers: {
          'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        }
      });

      // 解析 HTML
      const $ = cheerio.load(response.data);
      const title = $('title').text().trim();
      const content = $(this.selector).text().trim();

      // 生成安全文件名
      const safeFilename = title.replace(/[^a-z0-9]/gi, '_').toLowerCase() + '.txt';
      
      // 创建输出目录
      if (!fs.existsSync(this.outputDir)) {
        fs.mkdirSync(this.outputDir, { recursive: true });
      }

      // 保存内容
      fs.writeFileSync(
        `${this.outputDir}${safeFilename}`,
        `标题: ${title}\n\n正文:\n${content}`
      );

      console.log(`已保存: ${safeFilename}`);

      // 提取页面中的链接进行递归爬取
      $('a').each((i, el) => {
        const href = $(el).attr('href');
        if (href) {
          const absoluteUrl = new URL(href, url).href;
          if (absoluteUrl.startsWith(this.baseUrl)) {
            this.crawl(absoluteUrl);
          }
        }
      });

    } catch (error) {
      console.error(`爬取失败: ${url}`, error.message);
    }
  }
}

// 使用示例
const crawler = new WebCrawler({
  baseUrl: 'https://example.com', // 替换为目标网站
  selector: '.article-content',   // 替换为文章内容的选择器
  outputDir: './crawled_articles/'
});

// 启动爬虫
crawler.crawl('https://example.com/initial-page'); // 替换为起始页