-- ============================================================
-- SQL Script to clean up:
-- 1. Download buttons & PDF links (articles, blogs, career_facts)
-- 2. "Email: manu@localhost/aplha_m/public_html" (articles, blogs, career_facts)
-- Database: AlphaMindz
-- ============================================================

-- ------------------------------------------------------------
-- 1. Remove Download Button Blocks & Trailing Spacers
-- ------------------------------------------------------------
-- Articles
UPDATE `articles`
SET `content` = REGEXP_REPLACE(`content`, '(?ms)\\s*<!-- wp:button.*?<!-- /wp:button -->(\\s*<!-- wp:spacer.*?<!-- /wp:spacer -->)?', '')
WHERE `content` LIKE '%DOWNLOAD%';

-- Blogs
UPDATE `blogs`
SET `content` = REGEXP_REPLACE(`content`, '(?ms)\\s*<!-- wp:button.*?<!-- /wp:button -->(\\s*<!-- wp:spacer.*?<!-- /wp:spacer -->)?', '')
WHERE `content` LIKE '%DOWNLOAD%';

-- Career Facts
UPDATE `career_facts`
SET `content` = REGEXP_REPLACE(`content`, '(?ms)\\s*<!-- wp:button.*?<!-- /wp:button -->(\\s*<!-- wp:spacer.*?<!-- /wp:spacer -->)?', '')
WHERE `content` LIKE '%DOWNLOAD%';


-- ------------------------------------------------------------
-- 2. Remove Any Remaining Button / PDF Download Elements
-- ------------------------------------------------------------
UPDATE `articles`
SET `content` = REGEXP_REPLACE(`content`, '(?ms)<div[^>]*class=[\'"][^\'"]*wp-block-button[^\'"]*[\'"][^>]*>.*?</div>', '')
WHERE `content` LIKE '%wp-block-button%';

UPDATE `blogs`
SET `content` = REGEXP_REPLACE(`content`, '(?ms)<div[^>]*class=[\'"][^\'"]*wp-block-button[^\'"]*[\'"][^>]*>.*?</div>', '')
WHERE `content` LIKE '%wp-block-button%';

UPDATE `career_facts`
SET `content` = REGEXP_REPLACE(`content`, '(?ms)<div[^>]*class=[\'"][^\'"]*wp-block-button[^\'"]*[\'"][^>]*>.*?</div>', '')
WHERE `content` LIKE '%wp-block-button%';


-- ------------------------------------------------------------
-- 3. Remove "Email: manu@localhost/aplha_m/public_html"
-- ------------------------------------------------------------
-- Standalone paragraph block in career_facts:
UPDATE `career_facts`
SET `content` = REGEXP_REPLACE(`content`, '(?ms)(\\s*<!-- wp:paragraph.*?-->)?\\s*<p><strong>manu@localhost/aplha_m/public_html</strong></p>(\\s*<!-- /wp:paragraph -->)?', '')
WHERE `content` LIKE '%manu@localhost%';

-- With leading line breaks & strong tags in author signatures:
UPDATE `articles` 
SET `content` = REGEXP_REPLACE(`content`, '<br[[:space:]]*/?>[[:space:]]*<strong>[[:space:]]*Email:[[:space:]]*manu@localhost/aplha_m/public_html[[:space:]]*</strong>', '') 
WHERE `content` LIKE '%manu@localhost%';

UPDATE `blogs` 
SET `content` = REGEXP_REPLACE(`content`, '<br[[:space:]]*/?>[[:space:]]*<strong>[[:space:]]*Email:[[:space:]]*manu@localhost/aplha_m/public_html[[:space:]]*</strong>', '') 
WHERE `content` LIKE '%manu@localhost%';

UPDATE `career_facts` 
SET `content` = REGEXP_REPLACE(`content`, '<br[[:space:]]*/?>[[:space:]]*<strong>[[:space:]]*Email:[[:space:]]*manu@localhost/aplha_m/public_html[[:space:]]*</strong>', '') 
WHERE `content` LIKE '%manu@localhost%';

-- With leading line break without strong:
UPDATE `articles` 
SET `content` = REGEXP_REPLACE(`content`, '<br[[:space:]]*/?>[[:space:]]*Email:[[:space:]]*manu@localhost/aplha_m/public_html[[:space:]]*', '') 
WHERE `content` LIKE '%manu@localhost%';

UPDATE `blogs` 
SET `content` = REGEXP_REPLACE(`content`, '<br[[:space:]]*/?>[[:space:]]*Email:[[:space:]]*manu@localhost/aplha_m/public_html[[:space:]]*', '') 
WHERE `content` LIKE '%manu@localhost%';

UPDATE `career_facts` 
SET `content` = REGEXP_REPLACE(`content`, '<br[[:space:]]*/?>[[:space:]]*Email:[[:space:]]*manu@localhost/aplha_m/public_html[[:space:]]*', '') 
WHERE `content` LIKE '%manu@localhost%';


-- ------------------------------------------------------------
-- 4. Final Fallback Safety Replacement (Plain Text)
-- ------------------------------------------------------------
UPDATE `articles` SET `content` = REPLACE(`content`, 'Email: manu@localhost/aplha_m/public_html', '') WHERE `content` LIKE '%manu@localhost%';
UPDATE `blogs` SET `content` = REPLACE(`content`, 'Email: manu@localhost/aplha_m/public_html', '') WHERE `content` LIKE '%manu@localhost%';
UPDATE `career_facts` SET `content` = REPLACE(`content`, 'Email: manu@localhost/aplha_m/public_html', '') WHERE `content` LIKE '%manu@localhost%';

UPDATE `articles` SET `content` = REPLACE(`content`, 'manu@localhost/aplha_m/public_html', '') WHERE `content` LIKE '%manu@localhost%';
UPDATE `blogs` SET `content` = REPLACE(`content`, 'manu@localhost/aplha_m/public_html', '') WHERE `content` LIKE '%manu@localhost%';
UPDATE `career_facts` SET `content` = REPLACE(`content`, 'manu@localhost/aplha_m/public_html', '') WHERE `content` LIKE '%manu@localhost%';

-- ------------------------------------------------------------
-- 5. Clean up localhost/127.0.0.1 image URLs that trigger Chrome PNA permission prompt
-- ------------------------------------------------------------
UPDATE `articles` SET `image_url` = NULL WHERE `image_url` LIKE '%localhost%' OR `image_url` LIKE '%127.0.0.1%';
UPDATE `blogs` SET `image` = NULL WHERE `image` LIKE '%localhost%' OR `image` LIKE '%127.0.0.1%';
UPDATE `career_facts` SET `image` = NULL WHERE `image` LIKE '%localhost%' OR `image` LIKE '%127.0.0.1%';

UPDATE `articles` SET `content` = REGEXP_REPLACE(`content`, '<figure[^>]*>\\s*<img[^>]+src=[\'"][^\'"]*(localhost|127\\.0\\.0\\.1)[^\'"]*[\'"][^>]*>\\s*</figure>', '') WHERE `content` LIKE '%localhost%';
UPDATE `blogs` SET `content` = REGEXP_REPLACE(`content`, '<figure[^>]*>\\s*<img[^>]+src=[\'"][^\'"]*(localhost|127\\.0\\.0\\.1)[^\'"]*[\'"][^>]*>\\s*</figure>', '') WHERE `content` LIKE '%localhost%';
UPDATE `career_facts` SET `content` = REGEXP_REPLACE(`content`, '<figure[^>]*>\\s*<img[^>]+src=[\'"][^\'"]*(localhost|127\\.0\\.0\\.1)[^\'"]*[\'"][^>]*>\\s*</figure>', '') WHERE `content` LIKE '%localhost%';
