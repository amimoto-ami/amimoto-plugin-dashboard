#!/usr/bin/env node

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');
const inquirer = require('inquirer');
const semver = require('semver');
const simpleGit = require('simple-git');

const git = simpleGit();
const rootDir = path.resolve(__dirname, '..');
const readmePath = path.join(rootDir, 'readme.txt');
const phpPath = path.join(rootDir, 'amimoto-plugin-dashboard.php');
const changelogPath = path.join(rootDir, 'CHANGELOG.md');

// 現在のバージョンを取得
function getCurrentVersion() {
  // readme.txtから取得
  const readmeContent = fs.readFileSync(readmePath, 'utf8');
  const stableTagMatch = readmeContent.match(/Stable tag:\s*([^\s]+)/);
  if (stableTagMatch) {
    return stableTagMatch[1];
  }
  
  // PHPファイルから取得
  const phpContent = fs.readFileSync(phpPath, 'utf8');
  const versionMatch = phpContent.match(/Version:\s*([^\s]+)/);
  if (versionMatch) {
    return versionMatch[1];
  }
  
  throw new Error('バージョンが見つかりません');
}

// readme.txtのバージョンを更新
function updateReadmeVersion(oldVersion, newVersion) {
  let content = fs.readFileSync(readmePath, 'utf8');
  
  // Stable tagを更新
  content = content.replace(
    /Stable tag:\s*[^\s]+/,
    `Stable tag: ${newVersion}`
  );
  
  // Changelogセクションの先頭に新しいバージョンを追加
  const changelogHeader = `== Changelog ==`;
  const changelogIndex = content.indexOf(changelogHeader);
  
  if (changelogIndex !== -1) {
    const afterChangelog = content.substring(changelogIndex + changelogHeader.length);
    const newChangelogEntry = `\n\n= ${newVersion} =\n* Version bump from ${oldVersion} to ${newVersion}`;
    content = content.substring(0, changelogIndex + changelogHeader.length) + newChangelogEntry + afterChangelog;
  }
  
  fs.writeFileSync(readmePath, content, 'utf8');
  console.log(`✓ readme.txtのバージョンを ${oldVersion} → ${newVersion} に更新しました`);
}

// PHPファイルのバージョンを更新
function updatePhpVersion(oldVersion, newVersion) {
  let content = fs.readFileSync(phpPath, 'utf8');
  content = content.replace(
    /Version:\s*[^\s]+/,
    `Version: ${newVersion}`
  );
  fs.writeFileSync(phpPath, content, 'utf8');
  console.log(`✓ amimoto-plugin-dashboard.phpのバージョンを ${oldVersion} → ${newVersion} に更新しました`);
}

// git tagを作成
async function createGitTag(version) {
  try {
    await git.addTag(`v${version}`);
    console.log(`✓ git tag v${version} を作成しました`);
  } catch (error) {
    console.error(`✗ git tagの作成に失敗しました: ${error.message}`);
    throw error;
  }
}

// git logからCHANGELOG.mdを生成
async function generateChangelog(newVersion) {
  try {
    let log;
    try {
      // 最新のタグを取得
      const tags = await git.tags();
      if (tags.latest) {
        // 最新のタグ以降のコミットログを取得
        log = await git.log({
          from: tags.latest,
          to: 'HEAD'
        });
      } else {
        // タグがない場合は全コミットを取得（最初のリリースの場合）
        log = await git.log();
      }
    } catch (error) {
      // タグ取得に失敗した場合は全コミットを取得
      log = await git.log();
    }
    
    let changelog = `# Changelog\n\n`;
    changelog += `## [${newVersion}] - ${new Date().toISOString().split('T')[0]}\n\n`;
    
    if (!log || log.all.length === 0) {
      changelog += `* Version bump to ${newVersion}\n`;
    } else {
      // コミットメッセージを整形（重複を避けるため、既に追加したメッセージを記録）
      const addedMessages = new Set();
      log.all.forEach(commit => {
        const message = commit.message.split('\n')[0].trim(); // 最初の行のみ
        // 空のメッセージやマージコミットをスキップ
        if (message && !message.startsWith('Merge ') && !addedMessages.has(message)) {
          changelog += `* ${message}\n`;
          addedMessages.add(message);
        }
      });
      
      // コミットがない場合はデフォルトメッセージを追加
      if (addedMessages.size === 0) {
        changelog += `* Version bump to ${newVersion}\n`;
      }
    }
    
    // 既存のCHANGELOG.mdがある場合は、先頭に追加
    if (fs.existsSync(changelogPath)) {
      const existingContent = fs.readFileSync(changelogPath, 'utf8');
      // "# Changelog" の行を探して、その後に挿入
      const headerIndex = existingContent.indexOf('# Changelog');
      if (headerIndex !== -1) {
        const afterHeader = existingContent.substring(headerIndex + '# Changelog'.length);
        changelog = changelog.trim() + '\n' + afterHeader;
      } else {
        // ヘッダーがない場合は既存の内容を追加
        changelog = changelog.trim() + '\n\n' + existingContent;
      }
    }
    
    fs.writeFileSync(changelogPath, changelog, 'utf8');
    console.log(`✓ CHANGELOG.mdを生成しました`);
  } catch (error) {
    console.error(`✗ CHANGELOG.mdの生成に失敗しました: ${error.message}`);
    throw error;
  }
}

// メイン処理
async function main() {
  try {
    // 現在のバージョンを取得
    const currentVersion = getCurrentVersion();
    console.log(`現在のバージョン: ${currentVersion}`);
    
    // バージョンタイプを選択
    const { versionType } = await inquirer.prompt([
      {
        type: 'list',
        name: 'versionType',
        message: 'バージョンタイプを選択してください:',
        choices: [
          { name: `major (${currentVersion} → ${semver.inc(currentVersion, 'major')})`, value: 'major' },
          { name: `minor (${currentVersion} → ${semver.inc(currentVersion, 'minor')})`, value: 'minor' },
          { name: `patch (${currentVersion} → ${semver.inc(currentVersion, 'patch')})`, value: 'patch' },
          { name: 'カスタムバージョンを入力', value: 'custom' }
        ]
      }
    ]);
    
    let newVersion;
    if (versionType === 'custom') {
      const { customVersion } = await inquirer.prompt([
        {
          type: 'input',
          name: 'customVersion',
          message: 'バージョンを入力してください:',
          validate: (input) => {
            if (!semver.valid(input)) {
              return '有効なセマンティックバージョンを入力してください (例: 1.0.0)';
            }
            return true;
          }
        }
      ]);
      newVersion = customVersion;
    } else {
      newVersion = semver.inc(currentVersion, versionType);
    }
    
    console.log(`\n新しいバージョン: ${newVersion}\n`);
    
    // 確認
    const { confirm } = await inquirer.prompt([
      {
        type: 'confirm',
        name: 'confirm',
        message: 'このバージョンで更新を続行しますか？',
        default: true
      }
    ]);
    
    if (!confirm) {
      console.log('キャンセルしました');
      process.exit(0);
    }
    
    // ファイルを更新
    updateReadmeVersion(currentVersion, newVersion);
    updatePhpVersion(currentVersion, newVersion);
    
    // git tagを作成
    await createGitTag(newVersion);
    
    // CHANGELOG.mdを生成
    await generateChangelog(newVersion);
    
    console.log(`\n✓ バージョン更新が完了しました: ${currentVersion} → ${newVersion}`);
    console.log(`\n次のステップ:`);
    console.log(`  1. git add .`);
    console.log(`  2. git commit -m "chore: bump version to ${newVersion}"`);
    console.log(`  3. git push origin main --tags`);
    
  } catch (error) {
    console.error(`\n✗ エラーが発生しました: ${error.message}`);
    process.exit(1);
  }
}

main();

